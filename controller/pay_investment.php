<?php
session_start();
date_default_timezone_set("Africa/Lagos");

$user = htmlspecialchars(stripslashes($_POST['posted']));
$receipt = htmlspecialchars(stripslashes($_POST['invoice']));
$customer = htmlspecialchars(stripslashes($_POST['customer']));
$store = htmlspecialchars(stripslashes($_POST['store']));
$mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
$balance = floatval($_POST['balance']);
$amount = floatval($_POST['amount']);
$investment = htmlspecialchars(stripslashes($_POST['investment']));
$bank = htmlspecialchars(stripslashes($_POST['bank']));
$trans_date = htmlspecialchars(stripslashes($_POST['trans_date']));
$details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
$trans_type = "Field Documentation Payment";
$date = date("Y-m-d H:i:s");
$company = $_SESSION['company'];

/* Generate transaction number */
$todays_date = date("dmyhis");
$ran_num = mt_rand(100, 999);
$trx_num = "TR".$ran_num.$todays_date;

$data = array(
    'posted_by'   => $user,
    'customer'    => $customer,
    'payment_mode'=> $mode,
    'amount'      => $amount,
    'details'     => 'Investment payment',
    'invoice'     => $receipt,
    'store'       => $store,
    'bank'        => $bank,
    'trx_type'    => $trans_type,
    'trans_date'  => $trans_date,
    'post_date'   => $date,
    'trx_number'  => $trx_num
);

/* Classes */
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/inserts.php";
include "../classes/update.php";
require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";

$get = new selects();

/* Insert payment into deposits */
$add_data = new add_data('deposits', $data);
$add_data->create_data();

if(!$add_data){
    exit("Error posting payment.");
}

/* Record customer trail */
$trail_data = array(
    'customer'  => $customer,
    'store'     => $store,
    'description'=>$trans_type,
    'amount'    => $amount,
    'posted_by' => $user,
    'trx_number'=> $trx_num,
    'post_date' => $date
);
$add_trail = new add_data('customer_trail', $trail_data);
$add_trail->create_data();

/* Fetch investment details */
$inv = $get->fetch_details_cond('investments', 'investment_id', $investment);
foreach($inv as $loan){
    $invest_amount = $loan->amount;
    $currency = $loan->currency;
    $duration = $loan->duration;
    $total_in_naira = $loan->total_in_naira;
}

/* Insert into investment payments */
$inv_data = array(
    'investment'  => $investment,
    'customer'    => $customer,
    'amount'      => $amount,
    'amount_in_naira'=> $amount,
    'currency'    => $currency,
    'trx_date'    => $trans_date,
    'trx_number'  => $trx_num,
    'invoice'     => $receipt,
    'payment_mode'=> $mode,
    'bank'        => $bank,
    'post_date'   => $date,
    'posted_by'   => $user,
    'store'       => $store
);
$add_inv = new add_data('investment_payments', $inv_data);
$add_inv->create_data();

/* Get customer details */
$cust = $get->fetch_details_cond('customers', 'customer_id', $customer);
foreach($cust as $bal){
    $ledger          = $bal->acn;
    $customer_email  = $bal->customer_email;
    $client          = $bal->customer;
}

/* Ledger details */
$types = $get->fetch_details_cond('ledgers', 'acn', $ledger);
foreach($types as $t){
    $ledger_type  = $t->account_group;
    $ledger_group = $t->sub_group;
    $ledger_class = $t->class;
}

/* Cash or bank */
if($mode == "Cash"){
    $ledger_name = "CASH ACCOUNT";
}else{
    $bnk = $get->fetch_details_group('banks', 'bank', 'bank_id', $bank);
    $ledger_name = $bnk->bank;
}

$invs = $get->fetch_details_cond('ledgers', 'ledger', $ledger_name);
foreach($invs as $iv){
    $dr_ledger = $iv->acn;
    $dr_type   = $iv->account_group;
    $dr_group  = $iv->sub_group;
    $dr_class  = $iv->class;
}

/* Debit entry */
$debit_data = array(
    'account'   => $dr_ledger,
    'account_type'=> $dr_type,
    'sub_group' => $dr_group,
    'class'     => $dr_class,
    'details'   => 'Investment payment',
    'debit'     => $amount,
    'post_date' => $date,
    'posted_by' => $user,
    'trx_number'=> $trx_num,
    'trans_date'=> $trans_date,
    'store'     => $store
);
$add_debit = new add_data('transactions', $debit_data);
$add_debit->create_data();

/* Credit entry */
$credit_data = array(
    'account'     => $ledger,
    'account_type'=> $ledger_type,
    'sub_group'   => $ledger_group,
    'class'       => $ledger_class,
    'details'     => 'Investment Payment',
    'credit'      => $amount,
    'post_date'   => $date,
    'posted_by'   => $user,
    'trx_number'  => $trx_num,
    'trans_date'  => $trans_date,
    'store'       => $store
);
$add_credit = new add_data('transactions', $credit_data);
$add_credit->create_data();

/* Cashflow */
$flow_data = array(
    'account'   => $dr_ledger,
    'details'   => 'Investment Payment',
    'trx_number'=> $trx_num,
    'amount'    => $amount,
    'trans_type'=> 'inflow',
    'activity'  => 'operating',
    'post_date' => $date,
    'posted_by' => $user,
    'store'     => $store
);
$add_flow = new add_data('cash_flows', $flow_data);
$add_flow->create_data();

/* Calculate remaining balance */
$new_balance = $balance - $amount;

/* FULL PAYMENT = generate schedule */
$return_schedule_html = "";

if($new_balance <= 0){
    $upd = new Update_table();
    $upd->update('investments', 'contract_status', 'investment_id', 1, $investment);

    $return_schedule_html .= "<h3>Your Returns Schedule</h3><ul>";

    $start = new DateTime($date);
    $first_due = (clone $start)->modify('+12 months');

    /* First return = 30% */
    $ret1 = 0.30 * $total_in_naira;
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

    $return_schedule_html .= "<li>₦".number_format($ret1,2)." due on ".$first_due->format('jS F, Y')."</li>";

    /* every 6 months after */
    $installments = ($duration - 1) * 2;

    $current = clone $first_due;

    for($i = 1; $i <= $installments; $i++){
        $current = (clone $current)->modify('+6 months');
        $ret = 0.15 * $total_in_naira;

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

        $return_schedule_html .= "<li>₦".number_format($ret,2)." due on ".$current->format('jS F, Y')."</li>";
    }

    $return_schedule_html .= "</ul>";
}

/* SUM total paid */
$sum = $get->fetch_sum_single('investment_payments','amount_in_naira','investment',$investment);
$total_paid = $sum[0]->total ?? 0;

$fmt_total_cost  = number_format($invest_amount, 2);
$fmt_total_paid  = number_format($total_paid, 2);
$fmt_total_naira = number_format($total_in_naira, 2);
$fmt_remaining   = number_format($total_in_naira - $total_paid, 2);

$icon = ($currency == "Dollar") ? "$" : "₦";

/* BUILD EMAIL */
if($total_paid >= $total_in_naira){
     // FULL PAYMENT NOTIFICATION
    $notif_data = array(
        'client' => $customer,
        'subject' => 'Concentrate Investment Payment Completed',
        'message' => 'Dear ' . $client . ', your investment of ' . $icon . $fmt_total_cost . ' has been fully paid.',
        'post_date' => $date,
    );
    (new add_data('notifications', $notif_data))->create_data();
    $email_message = "
        <p>Dear $client,</p>
        <p>Congratulations! Your investment has been <strong>fully paid</strong>.</p>

        <h3>Investment Summary:</h3>
        <ul>
            <li><strong>Duration:</strong> $duration years</li>
            <li><strong>Currency:</strong> $currency</li>
            <li><strong>Total Investment:</strong> $icon$fmt_total_cost</li>
            <li><strong>Naira Value:</strong> ₦$fmt_total_naira</li>
        </ul>

        $return_schedule_html

        <p>Thank you for trusting <strong>Davidorlah Nigeria Ltd</strong>.</p>
    ";

} else {
    // PART PAYMENT NOTIFICATION
    $notif_data = array(
        'client' => $customer,
        'subject' => 'Concentrate Investment Payment Update',
        'message' => 'Dear '.$client.', your investment payment has been received. Total paid: ₦'.$fmt_total_paid.' | Remaining: ₦'.$fmt_remaining,
        'post_date' => $date,
    );
    (new add_data('notifications', $notif_data))->create_data();
    $email_message = "
        <p>Dear $client,</p>
        <p>Your investment payment has been received.</p>

        <ul>
            <li><strong>Total Investment:</strong> $icon$fmt_total_cost</li>
            <li><strong>Total Paid:</strong> ₦$fmt_total_paid</li>
            <li><strong>Balance Remaining:</strong> ₦$fmt_remaining</li>
        </ul>
    ";
}

/* Send Email */
function smtpmailer($to, $from, $from_name, $subject, $body){
    $mail = new PHPMailer();
    $mail->IsSMTP();
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';
    $mail->Host = 'www.dorthprosuite.com';
    $mail->Port = 465;
    $mail->Username = 'admin@dorthprosuite.com';
    $mail->Password = 'yMcmb@her0123!';

    $mail->IsHTML(true);
    $mail->From     = $from;
    $mail->FromName = $from_name;

    $mail->AddAddress($to);
    $mail->Subject = $subject;
    $mail->Body    = $body;

    return $mail->Send() ? "Mail sent" : "Mail failed";
}

smtpmailer($customer_email, "admin@dorthprosuite.com", $company,
    "Investment Payment Confirmation",
    $email_message
);
?>
<div id="printBtn">
    <button onclick="printInvestmentReceipt('<?php echo $receipt?>')">Print Receipt <i class="fas fa-print"></i></button>
</div>

<?php
echo "<p style='color:green;margin:5px 50px'>Payment posted successfully!</p>";

?>
