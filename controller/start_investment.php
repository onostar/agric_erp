<?php
session_start();
date_default_timezone_set("Africa/Lagos");

$user = $_SESSION['user_id'];
$store = $_SESSION['store_id'];
$date = date("Y-m-d H:i:s");

$customer = htmlspecialchars(stripslashes($_POST['customer']));
$duration = htmlspecialchars(stripslashes($_POST['duration']));
$amount = htmlspecialchars(stripslashes($_POST['amount']));
$currency = htmlspecialchars(stripslashes($_POST['currency']));
$total_dollar = htmlspecialchars(stripslashes($_POST['total_dollar']));
$exchange_rate = htmlspecialchars(stripslashes($_POST['exchange_rate']));
$units = htmlspecialchars(stripslashes($_POST['units']));
/* if($currency == "Dollar"){
    $exchange_rate = $rate;
}else{
    $exchange_rate = 0;
} */

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
$checks = $get_details->fetch_count_2cond('investments', 'customer', $customer, 'contract_status', 0);
if($checks > 0){
    echo "<script>alert('The selected Client has a pending investment yet to be activated! Kindly proceed to payment in order to activate the pending investment before proceeding to start a new investment')</script>";
    echo "<div class='success'><p style='background:red'>The selected Client has a pending investment yet to be activated! Kindly make proceed to payment in order to activate the pending investment before proceeding to start a new investment! <i class='fas fa-thumbs-down'></i></p></div>";
    exit;
}else{
$add_data = new add_data('investments', $data);
$add_data->create_data();

// get customer details
$cus = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
foreach($cus as $cu){
    $client = $cu->customer;
    $customer_email = $cu->customer_email;
}
if($currency == "Dollar"){
    $icon = "$";
}else{
    $icon = "₦";
}

    // formatting
    $amount_fmt = number_format($amount, 2);
    $rate_fmt = number_format($exchange_rate, 2);
    $due_fmt = number_format($total_dollar, 2);

    // build  message
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

    <br>
    <p>Thank you for trusting <strong>Davidorlah Nigeria Limited</strong> with your investment.</p>

    <p>Warm regards,<br>
    <strong>Investment Management Team</strong><br>
    Davidorlah Nigeria Limited</p>
    ";


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

    echo "<div class='success'><p>Concentrate investment posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
}
?>
