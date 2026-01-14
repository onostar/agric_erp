<?php
session_start();
date_default_timezone_set("Africa/Lagos");

// --- sanitize input ---
$user = htmlspecialchars(stripslashes($_POST['posted'] ?? ''));
$receipt = htmlspecialchars(stripslashes($_POST['invoice'] ?? ''));
$customer = htmlspecialchars(stripslashes($_POST['customer'] ?? ''));
$store = htmlspecialchars(stripslashes($_POST['store'] ?? ''));
$mode = htmlspecialchars(stripslashes($_POST['payment_mode'] ?? ''));
$balance = htmlspecialchars(stripslashes($_POST['balance'] ?? 0));
$amount = htmlspecialchars(stripslashes($_POST['amount'] ?? 0)); // principal in original currency
// $amount_in_naira = htmlspecialchars(stripslashes($_POST['amount_in_naira'] ?? 0)); // converted naira value
$investment = htmlspecialchars(stripslashes($_POST['investment'] ?? ''));
$bank = htmlspecialchars(stripslashes($_POST['bank'] ?? ''));
$trans_date = htmlspecialchars(stripslashes($_POST['trans_date'] ?? date("Y-m-d")));
$details = ucwords(htmlspecialchars(stripslashes($_POST['details'] ?? 'Principal Return')));
$trans_type = "Investment Principal Return";
$date = date("Y-m-d H:i:s");
$company = $_SESSION['company'] ?? '';

// --- generate transaction number ---
$todays_date = date("dmyhis");
$ran_num = "";
for ($i = 0; $i < 3; $i++) {
    $ran_num .= random_int(0, 9);
}
$trx_num = "TR" . $ran_num . $todays_date;



// --- includes / classes ---
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/inserts.php";
include "../classes/update.php";

require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";

$get_details = new selects();
// --- fetch investment details (for email summary) ---
$inv_rows = $get_details->fetch_details_cond('investments', 'investment_id', $investment);
$currency = '';
$duration = '';
$rate = 0;
$invest_ref = "DAV/CON/00" . $investment;
foreach ($inv_rows as $inv_row) {
    $currency = $inv_row->currency ?? '';
    $duration = $inv_row->duration ?? '';
    $rate = $inv_row->exchange_rate ?? '';
    // optionally fetch more fields if available (e.g., investment_reference)
}
/* if($currency == "Dollar"){
    $amount_in_naira = $amount * $rate; // convert to naira
}else{
    $amount_in_naira = $amount;
} */
// --- prepare deposit record (amount_in_naira is the numeric amount posted) ---
$data = array(
    'posted_by' => $user,
    'customer' => $customer,
    'payment_mode' => $mode,
    'amount' => $amount, // original currency principal
    'details' => 'Principal Return',
    'invoice' => $receipt,
    'store' => $store,
    'bank' => $bank,
    'trx_type' => $trans_type,
    'trans_date' => $trans_date,
    'post_date' => $date,
    'trx_number' => $trx_num
);
// --- insert deposit record ---
$add_deposit = new add_data('deposits', $data);
$add_deposit->create_data();

if ($add_deposit) {
    // --- customer trail ---
    $trail_data = array(
        'customer' => $customer,
        'store' => $store,
        'description' => $trans_type,
        'amount' => $amount,
        'posted_by' => $user,
        'trx_number' => $trx_num,
        'post_date' => $date
    );
    $add_trail = new add_data('customer_trail', $trail_data);
    $add_trail->create_data();

    

    // --- insert principal return record ---
    
    $principal_data = array(
        'investment' => $investment,
        'client' => $customer,
        'currency' => $currency,
        'amount' => $amount, // original currency principal
        'value_in_naira' => $amount, // naira equivalent
        'trx_date' => $trans_date,
        'trx_number' => $trx_num,
        'invoice' => $receipt,
        'payment_mode' => $mode,
        'bank' => $bank,
        'post_date' => $date,
        'posted_by' => $user,
        'store' => $store
    );
    $add_principal = new add_data('principal_returns', $principal_data);
    $add_principal->create_data();

    // --- update principal status on investment ---
    (new Update_table())->update('investments', 'principal', 'investment_id', 1, $investment);

    // --- accounting entries (debit customer ledger, credit cash/bank) ---
    $bals = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
    $ledger = null;
    $customer_email = '';
    $client_name = '';
    foreach ($bals as $bal) {
        $ledger = $bal->acn ?? null;
        $customer_email = $bal->customer_email ?? '';
        $client_name = $bal->customer ?? '';
    }

    // ledger account metadata
    $ledger_type = $ledger_group = $ledger_class = null;
    if ($ledger) {
        $types = $get_details->fetch_details_cond('ledgers', 'acn', $ledger);
        foreach ($types as $type) {
            $ledger_type = $type->account_group ?? null;
            $ledger_group = $type->sub_group ?? null;
            $ledger_class = $type->class ?? null;
        }
    }

    // contra ledger (cash or bank)
    if ($mode === "Cash") {
        $ledger_name = "CASH ACCOUNT";
    } else {
        $bnk = $get_details->fetch_details_group('banks', 'bank', 'bank_id', $bank);
        $ledger_name = $bnk->bank ?? 'BANK';
    }
    $invs = $get_details->fetch_details_cond('ledgers', 'ledger', $ledger_name);
    $dr_ledger = $dr_type = $dr_group = $dr_class = null;
    foreach ($invs as $inv) {
        $dr_ledger = $inv->acn ?? null;
        $dr_type = $inv->account_group ?? null;
        $dr_group = $inv->sub_group ?? null;
        $dr_class = $inv->class ?? null;
    }

    // create transactions (debit customer, credit cash/bank)
    $debit_data = array(
        'account' => $ledger,
        'account_type' => $ledger_type,
        'sub_group' => $ledger_group,
        'class' => $ledger_class,
        'details' => 'Investment Principal Return',
        'debit' => $amount,
        'post_date' => $date,
        'posted_by' => $user,
        'trx_number' => $trx_num,
        'trans_date' => $trans_date,
        'store' => $store
    );
    $credit_data = array(
        'account' => $dr_ledger,
        'account_type' => $dr_type,
        'sub_group' => $dr_group,
        'class' => $dr_class,
        'details' => 'Investment Principal Return',
        'credit' => $amount,
        'post_date' => $date,
        'posted_by' => $user,
        'trx_number' => $trx_num,
        'trans_date' => $trans_date,
        'store' => $store
    );

    $add_debit = new add_data('transactions', $debit_data);
    $add_credit = new add_data('transactions', $credit_data);
    $add_credit->create_data();

    // --- cash flow entry (financing outflow) ---
    $flow_data = array(
        'account' => $dr_ledger,
        'details' => 'Investment Principal Return',
        'trx_number' => $trx_num,
        'amount' => $amount,
        'trans_type' => 'outflow',
        'activity' => 'financing',
        'post_date' => $date,
        'posted_by' => $user,
        'store' => $store
    );
    $add_flow = new add_data('cash_flows', $flow_data);
    $add_flow->create_data();

    // --- formatting for email ---
    $fmt_principal = number_format((float)$amount, 2);
    $fmt_naira = number_format((float)$amount_in_naira, 2);
    $trx_date_formatted = date("jS F Y, h:ia", strtotime($trans_date));

    // --- Option B: Detailed Investment Summary (email body) ---
    if($currency == "Dollar"){
        $icon = "$";
    }else{
        $icon = "₦";
    }
    $email_message = "
        <p>Dear {$client_name},</p>

        <p>We confirm that your investment principal has been returned successfully. Below is the summary of the transaction:</p>

        <h3>Investment Principal Return Summary</h3>
        <ul>
            <li><strong>Investment ID:</strong> {$invest_ref}</li>
            <li><strong>Principal Amount:</strong> {$icon} {$fmt_principal}</li>
            <li><strong>Contract Duration:</strong> " . htmlspecialchars($duration) . " Years</li>
            <li><strong>Transaction Date:</strong> {$trx_date_formatted}</li>
            <li><strong>Transaction ID:</strong> {$receipt}</li>
        </ul>

        <p>If you have any questions or require further documentation, please contact our support team.</p>

        <p>Thank you for your trust in <strong>{$company}</strong>.</p>

        <p>Warm regards,<br>
        {$company} - Customer Support</p>
    ";

    // --- notification record (consistent text) ---
    $notif_message = "Dear {$client_name}, Your investment principal of {$icon} {$fmt_principal} has been returned on {$trx_date_formatted}. Transaction ID: {$receipt}. Thank you for investing with {$company}.";

    $notif_data = array(
        'client' => $customer,
        'subject' => 'Investment Principal Return Confirmation',
        'message' => $notif_message,
        'post_date' => $date
    );
    (new add_data('notifications', $notif_data))->create_data();

    // --- send email (PHPMailer) ---
    function smtpmailer($to, $from, $from_name, $subject, $body) {
        $mail = new PHPMailer();
        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = 'ssl';
        // Use the correct SMTP host for your provider. Avoid 'www.' prefix.
        $mail->Host = 'dorthprosuite.com';
        $mail->Port = 465;
        $mail->Username = 'admin@dorthprosuite.com';
        $mail->Password = 'yMcmb@her0123!'; // move to env in production

        $mail->IsHTML(true);
        $mail->From = $from;
        $mail->FromName = $from_name;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;

        $mail->AddAddress($to);
        // internal copy
        $mail->AddAddress('onostarmedia@gmail.com');

        if (!$mail->Send()) {
            return ['success' => false, 'error' => $mail->ErrorInfo];
        }
        return ['success' => true];
    }

    $to = $customer_email;
    $from = 'admin@dorthprosuite.com';
    $from_name = $company ?: 'Company';
    $subject = 'Investment Principal Return Confirmation - ' . ($company ?: 'Company');
    $msg = $email_message;

    $mail_result = smtpmailer($to, $from, $from_name, $subject, $msg);

    // --- final UI / response ---
    // echo "<div id='printBtn'><button onclick=\"printDocReceipt('{$receipt}')\">Print Receipt <i class='fas fa-print'></i></button></div>";

    if ($mail_result['success']) {
        echo "<div class='success'><p style='color:#fff; margin:5px 50px'>Payment posted successfully! Email notification sent.</p></div>";
    } else {
        $err = htmlspecialchars($mail_result['error']);
        echo "<div class='success'><p style='color:orange; margin:5px 50px'>Payment posted successfully, but email failed to send: {$err}</p></div>";
    }
} else {
    echo "<p style='color:red; margin:5px 50px'>Failed to record payment. Please try again.</p>";
}
?>
