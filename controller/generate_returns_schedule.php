<?php
session_start();
date_default_timezone_set("Africa/Lagos");

$user = $_SESSION['user_id'];
$store = $_SESSION['store_id'];
$customer = htmlspecialchars(stripslashes($_POST['customer']));
$investment = htmlspecialchars(stripslashes($_POST['investment']));

$date = date("Y-m-d H:i:s");
$company = $_SESSION['company'];

/* Classes */
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/inserts.php";
include "../classes/update.php";
include "../classes/delete.php";

require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";

$get = new selects();

/* Fetch investment details */
$inv = $get->fetch_details_cond('investments', 'investment_id', $investment);
foreach($inv as $loan){
    $invest_amount = $loan->amount;
    $currency = $loan->currency;
    $duration = $loan->duration;
    $total_in_dollar = $loan->total_in_dollar;
    $exchange_rate = $loan->exchange_rate;
    $units = $loan->units;
    $start_date = $loan->start_date;
}
//get amount paid so far
$sum_paid = $get->fetch_sum_single('investment_payments', 'amount', 'investment', $investment);
$total_paid_so_far = $sum_paid[0]->total ?? 0;

/* newinvestment amount */
$balance = $invest_amount - $total_paid_so_far;
$new_invest_amount = $total_paid_so_far;
$return_schedule_html = "";
//delete previous schedule
$del = new deletes();
$del->delete_item('investment_returns', 'investment_id', $investment);
if($currency == "Dollar"){
    $total_in_dollar = $new_invest_amount;
}else{
    $total_in_dollar = $new_invest_amount * $exchange_rate;
}
$new_units = $total_in_dollar / 2000; // $2000 per unit
//update investment with new amount
$update_investment = new Update_table();
$update_data = array(
    'amount'=> $new_invest_amount,
    'total_in_dollar'=> $total_in_dollar,
    'units'=> $new_units,
    'modified_by'=>$user,
    'date_modified'=>$date
);
$update_investment->updateAny('investments', $update_data, 'investment_id', $investment);
/* Generate new returns schedule based on new investment amount */

$return_schedule_html .= "<h3>Your Returns Schedule</h3><ul>";

$start = new DateTime($start_date);
$first_due = (clone $start)->modify('+12 months');

/* First return = 30% */
$ret1 = 0.30 * $new_invest_amount;
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

// $return_schedule_html .= "<li>₦".number_format($ret1,2)." due on ".$first_due->format('jS F, Y')."</li>";

/* every 6 months after */
$installments = ($duration - 1) * 2;

$current = clone $first_due;

for($i = 1; $i <= $installments; $i++){
    $current = (clone $current)->modify('+6 months');
    $ret = 0.15 * $new_invest_amount;

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

}

$return_schedule_html = "<h3>Your New Returns Schedule has been generated</h3>";
//fetch investment returns details for email
$invest_returns = $get->fetch_details_cond('investment_returns', 'investment_id', $investment);
foreach($invest_returns as $ir){
    $due_date = date("jS F, Y", strtotime($ir->due_date));
    $percentage = $ir->percentage;
    $return = number_format($ir->amount_due, 2);
    $return_schedule_html .= "<li>₦".$return." due on ".$due_date."</li>";
}
$return_schedule_html .= "</ul>";


/* SUM total paid */
$sum = $get->fetch_sum_single('investment_payments','amount','investment',$investment);
$total_paid = $sum[0]->total ?? 0;

$fmt_total_cost  = number_format($invest_amount, 2);
$fmt_total_paid  = number_format($total_paid, 2);
$fmt_total_dollar = number_format($total_in_dollar, 2);
$fmt_remaining   = number_format($invest_amount - $total_paid, 2);

$icon = ($currency == "Dollar") ? "$" : "₦";
/* Get customer details */
$cust = $get->fetch_details_cond('customers', 'customer_id', $customer);
foreach($cust as $bal){
    $ledger          = $bal->acn;
    $customer_email  = $bal->customer_email;
    $client          = $bal->customer;
}
/* BUILD EMAIL */
$notif_data = array(
    'client' => $customer,
    'subject' => 'Investment Restructured & Returns Updated',
    'message' => 'Your investment was restructured due to incomplete payment within 30 days. A new returns schedule has been generated based on your actual amount paid.',
    'post_date' => $date,
);
(new add_data('notifications', $notif_data))->create_data();

    $email_message = "
<p>Dear $client,</p>

<p>
We hope this message finds you well.
</p>

<p>
This is to formally notify you that your investment could not be completed within the required
<strong>30-day payment window</strong>. As a result, your investment has been <strong>restructured</strong>
based on the actual amount paid.
</p>

<h3>Previous Investment Details</h3>
<ul>
    <li><strong>Original Investment Amount:</strong> $icon" . number_format($invest_amount, 2) . "</li>
    <li><strong>Amount Paid:</strong> $icon" . number_format($total_paid_so_far, 2) . "</li>
    <li><strong>Unpaid Balance:</strong> $icon" . number_format($balance, 2) . "</li>
</ul>

<h3>New Investment Details (Now Active)</h3>
<ul>
    <li><strong>New Investment Amount:</strong> $icon" . number_format($new_invest_amount, 2) . "</li>
    <li><strong>Currency:</strong> $currency</li>
    <li><strong>Investment Duration:</strong> $duration year(s)</li>
    <li><strong>Total Value (USD):</strong> $" . number_format($total_in_dollar, 2) . "</li>
    <li><strong>Units Allocated:</strong> " . number_format($new_units, 2) . " unit(s)</li>
</ul>

<p>
Please note that the <strong>previous returns schedule has been cancelled</strong>,
and a new returns schedule has been generated based on your updated investment value.
</p>

$return_schedule_html

<p>
If you have any questions or wish to top up your investment in the future,
please contact our support team.
</p>

<p>
Thank you for your continued trust in <strong>Davidorlah Nigeria Ltd</strong>.
</p>

<p>
Warm regards,<br>
<strong>$company</strong>
</p>
";



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
    "Investment Restructured & New Returns Schedule",
    $email_message
);
?>


<?php
echo "<div class='success'><p style='color:green;margin:5px 50px'>Returns Schedule generated successfully!</p></div>";

?>
