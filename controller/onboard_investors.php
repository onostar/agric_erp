<?php
session_start();
date_default_timezone_set("Africa/Lagos");

$user = $_SESSION['user_id'];
$store = $_SESSION['store_id'];
$date = date("Y-m-d H:i:s");

$customer = htmlspecialchars(stripslashes($_POST['customer']));
$duration = htmlspecialchars(stripslashes($_POST['duration']));
$amount = htmlspecialchars(stripslashes($_POST['amount']));
$amount_paid = htmlspecialchars(stripslashes($_POST['amount_paid']));
$currency = htmlspecialchars(stripslashes($_POST['currency']));
$total_dollar = htmlspecialchars(stripslashes($_POST['total_dollar']));
$exchange_rate = htmlspecialchars(stripslashes($_POST['exchange_rate']));
$units = htmlspecialchars(stripslashes($_POST['units']));
$start_date = htmlspecialchars(stripslashes($_POST['start_date']));
/* if($currency == "Dollar"){
    $exchange_rate = $rate;
}else{
    $exchange_rate = 0;
} */
//generate trx.number
$todays_date = date("dmyhis");
$ran_num ="";
for($i = 0; $i < 3; $i++){
    $random_num = random_int(0, 9);
    $ran_num .= $random_num;
}
$trx_num = "TR".$ran_num.$todays_date.$user;
$data = array(
    'customer' => $customer,
    'duration' => $duration,
    'currency' => $currency,
    'amount' => $amount,
    'total_in_dollar' => $total_dollar,
    'exchange_rate' => $exchange_rate,
    'units' => $units,
    'posted_by' => $user,
    'post_date' => $date,
    'trx_number' => $trx_num,
    // 'start_date' => $start_date,
    'store' => $store
);

require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/update.php";
include "../classes/inserts.php";

$get_details = new selects();
//check if there is an exisitng unpaid investment
$checks = $get_details->check_investment($customer);
if($checks > 0){
    echo "<script>alert('The selected Client has a pending investment yet to be activated! Kindly proceed to payment in order to activate the pending investment before proceeding to start a new investment');</script>";
    echo "<div class='success'><p style='background:red'>The selected Client has a pending investment yet to be activated! Kindly make proceed to payment in order to activate the pending investment before proceeding to start a new investment! <i class='fas fa-thumbs-down'></i></p></div>";
    exit;
}else{
$add_data = new add_data('investments', $data);
$add_data->create_data();

if($currency == "Dollar"){
    $icon = "$";
}else{
    $icon = "NGN";
}
//check if payment was made
if($amount_paid > 0){
    //get investment id
    $ids = $get_details->fetch_lastInsertedCon('investments', 'investment_id', 'trx_number', $trx_num);
    foreach($ids as $idss){
        $investment = $idss->investment_id;
    }
    

    $data = array(
        'posted_by'   => $user,
        'customer'    => $customer,
        'payment_mode'=> 'Cash',
        'amount'      => $amount_paid,
        'details'     => 'Investment payment',
        'invoice'     => $trx_num,
        'store'       => $store,
        'bank'        => 0,
        'trx_type'    => 'Investment Payment',
        'trans_date'  => $start_date,
        'post_date'   => $date,
        'trx_number'  => $trx_num
    );
    /* Insert payment into deposits */
    $add_data = new add_data('deposits', $data);
    $add_data->create_data();
    //get amount in dollar
    if($currency == "Dollar"){
        $amount_in_dollar = $amount_paid;
    }else{
        $amount_in_dollar = $amount_paid / $exchange_rate;
    }
    //insert into investment payment
    $inv_data = array(
        'investment'  => $investment,
        'customer'    => $customer,
        'amount'      => $amount_paid,
        'amount_in_dollar'=> $amount_in_dollar,
        'currency'    => $currency,
        'trx_date'    => $start_date,
        'trx_number'  => $trx_num,
        'invoice'     => $trx_num,
        'payment_mode'=> 'Cash',
        'bank'        => 0,
        'post_date'   => $date,
        'posted_by'   => $user,
        'store'       => $store
    );
    $add_inv = new add_data('investment_payments', $inv_data);
    $add_inv->create_data();
    //update contract
    $upd = new Update_table();
    $upd->update_double('investments', 'contract_status', 1, 'start_date', $start_date, 'investment_id', $investment);

    $return_schedule_html = "<h3>Your Returns Schedule</h3><ul>";

    $start = new DateTime($start_date);
    $first_due = (clone $start)->modify('+12 months');

    /* First return = 30% */
    $ret1 = 0.30 * $amount;
    $returns_data = array(
        'investment_id'=>$investment,
        'customer'=>$customer,
        'due_date'=>$first_due->format('Y-m-d'),
        'percentage'=>'30',
        'amount_due'=>$ret1,
        'store'=>$store,
        'posted_by'=>$user,
        'post_date'=>$date
    );
    $ins = new add_data('investment_returns', $returns_data);
    $ins->create_data();
    $return_schedule_html .= "<li>$icon".number_format($ret1, 2)." due on ".$first_due->format("jS F, Y")."</li>";
    /* every 6 months after */
    $installments = ($duration - 1) * 2;

    $current = clone $first_due;

    for($i = 1; $i <= $installments; $i++){
        $current = (clone $current)->modify('+6 months');
        $ret = 0.15 * $amount;

        $data_ret = array(
            'investment_id'=>$investment,
            'customer'=>$customer,
            'due_date'=>$current->format('Y-m-d'),
            'percentage'=>'15',
            'amount_due'=>$ret,
            'store'=>$store,
            'posted_by'=>$user,
            'post_date'=>$date
        );
        $ins2 = new add_data('investment_returns', $data_ret);
        $ins2->create_data();

        $return_schedule_html .= "<li>$icon".number_format($ret,2)." due on ".$current->format('jS F, Y')."</li>";
    }
    $return_schedule_html .= "</ul>";
    /* SUM total paid */
    $sum = $get_details->fetch_sum_single('investment_payments','amount','investment',$investment);
    $total_paid = $sum[0]->total ?? 0;
}
// get customer details
$cus = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
foreach($cus as $cu){
    $client = $cu->customer;
    $customer_email = $cu->customer_email;
}


    // formatting
    $amount_fmt = number_format($amount, 2);
    $rate_fmt = number_format($exchange_rate, 2);
    $due_fmt = number_format($total_dollar, 2);

    // build  message
    if($amount_paid > 0){
        $fmt_total_paid  = number_format($total_paid, 2);
        $fmt_remaining   = number_format($amount - $total_paid, 2);
        $balance = $amount - $total_paid;
        if($balance > 0){
            $payment_note = "You are expected to complete payment within 30 days.";
        }else{
            $payment_note = "";
        }
       
        $message = "<p>Dear $client,</p>

        <p>We are pleased to inform you that your <strong>Davidorlah Concentrate Production Investment</strong> has been successfully started and payment received.</p>

        <h3 style='color:green;'>Investment Summary</h3>
        <ul>
            <li><strong>Units Allocated:</strong> $units unit(s)</li>
            <li><strong>Investment Value:</strong> $icon$amount_fmt</li>
            <li><strong>Total Value in Dollar:</strong> $icon$due_fmt</li>
            <li><strong>Contract Duration:</strong> $duration Years</li>
            <li><strong>Total Paid:</strong> $icon$fmt_total_paid</li>
            <li><strong>Balance Remaining:</strong> $icon$fmt_remaining</li>
        </ul>

        <h3>Return Structure</h3>
        <ul>
            <li><strong>First 12 Months Return:</strong> 30% (paid after the first 12 months)</li>
            <li><strong>Subsequent Returns:</strong> 15% every 6 months</li>
            <li><strong>Total Contract Length:</strong> 36 months (3 years)</li>
        </ul>

        $return_schedule_html
        
        <p>$payment_note</p>

        <p>You may log in to your <strong><a href='https://davidorlah.dorthprosuite.com/client_portal/' target='_blank'>Investor Portal</a></strong> to view your investment status, expected returns, and payment history at any time.</p>

        <p>Your Username : $customer_email<br>
        Default Password : 123</p>

        <p><strong>N.B: The system will prompt you to change your password from the default password</strong></p>

        <br>
        <p>Thank you for trusting <strong>Davidorlah Nigeria Limited</strong> with your investment.</p>

        <p>Warm regards,<br>
        <strong>Investment Management Team</strong><br>
        Davidorlah Nigeria Limited</p>
        ";

    }else{
         $message = "<p>Dear $client,</p>

        <p>We are pleased to inform you that your <strong>Davidorlah Concentrate Production Investment</strong> has been successfully initiated.</p>

        <h3 style='color:green;'>Investment Summary</h3>
        <ul>
            <li><strong>Units Allocated:</strong> $units unit(s)</li>
            <li><strong>Investment Value:</strong> $icon$amount_fmt</li>
            <li><strong>Total Value in Dollar:</strong> $icon$due_fmt</li>
            <li><strong>Contract Duration:</strong> $duration Years</li>
        </ul>

        <h3>Return Structure</h3>
        <ul>
            <li><strong>First 12 Months Return:</strong> 30% (paid after the first 12 months)</li>
            <li><strong>Subsequent Returns:</strong> 15% every 6 months</li>
            <li><strong>Total Contract Length:</strong> 36 months (3 years)</li>
        </ul>

        <p>Your investment has now been logged and is awaiting payment to fully activate the contract. Once payment is confirmed, the countdown toward your first 12-month return begins.</p>
        <p>You are expected to complete payment within 30 days.</p>

        <p>You may log in to your <strong><a href='https://davidorlah.dorthprosuite.com/client_portal/' target='_blank'>Investor Portal</a></strong> to view your investment status, expected returns, and payment history at any time.</p>

          <p>Your Username : $customer_email<br>
        Default Password : 123</p>

        <p><strong>N.B: The system will prompt you to change your password from the default password</strong></p>

        <br>
        <p>Thank you for trusting <strong>Davidorlah Nigeria Limited</strong> with your investment.</p>

        <p>Warm regards,<br>
        <strong>Management</strong><br>
        Davidorlah Nigeria Limited</p>
        ";
    }
    // notification
    $notif_data = array(
        'client' => $customer,
        'subject' => 'Your Concentrate Investment Has Been Created',
        'message' => 'Dear '.$client.', your Concentrate Production Investment has been successfully created. '
                .'Please proceed with the investment payment to activate your 3-year contract. '
                .'You will earn 30% after the first 12 months and 15% every 6 months thereafter.',
        'post_date' => $date
    );
    $add_data = new add_data('notifications', $notif_data);
    $add_data->create_data();

    /* send mail */
    function smtpmailer($to, $from, $from_name, $subject, $body){
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true; 
        $mail->SMTPSecure = 'ssl'; 
        $mail->Host = 'smtppro.zoho.com';
        $mail->Port = 465; 
        $mail->Username = 'info@davidorlahfarms.com';
        $mail->Password = 'Info_DFarms@2520';   

        $mail->IsHTML(true);
        $mail->From="info@davidorlahfarms.com";
        $mail->FromName=$from_name;
        $mail->Sender=$from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);

        if(!$mail->Send()){
            return "Failed to send mail";
        } else {
            return "Message Sent Successfully";
        }
    }

    $to = $customer_email;
    $from = 'info@davidorlahfarms.com';
    $from_name = "Davidorlah Nigeria Limited";
    $subj = 'Your Concentrate Investment Contract Has Been Created';
    $msg = "<div>$message</div>";

    smtpmailer($to, $from, $from_name, $subj, $msg);

    echo "<div class='success'><p>Existing Client investment posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
}
?>
