<?php
session_start();
date_default_timezone_set("Africa/Lagos");
if(isset($_SESSION['user_id'])){

$user = $_SESSION['user_id'];
$farm = $_SESSION['store_id'];
$date = date("Y-m-d H:i:s");

$id = strtoupper(htmlspecialchars(stripslashes($_POST['field_id'])));
$customer = htmlspecialchars(stripslashes($_POST['customer']));
$duration = htmlspecialchars(stripslashes($_POST['duration']));
$payment_duration = htmlspecialchars(stripslashes($_POST['payment_duration']));
$purchase_cost = htmlspecialchars(stripslashes($_POST['purchase_cost']));
$discount = htmlspecialchars(stripslashes($_POST['discount']));
$size = htmlspecialchars(stripslashes($_POST['field_size']));
$total_due = htmlspecialchars(stripslashes($_POST['total_due']));
$documentation = htmlspecialchars(stripslashes($_POST['documentation']));
$rent_percentage = htmlspecialchars(stripslashes($_POST['rent_percentage']));
$annual_rent = htmlspecialchars(stripslashes($_POST['annual_rent']));
$start = htmlspecialchars(stripslashes($_POST['start_date']));
$installment_amount = htmlspecialchars(stripslashes($_POST['installment_amount']));

// determine total installments
if($payment_duration == "3"){
    $installments = 3;
} elseif($payment_duration == "6"){
    $installments = 6;
} elseif($payment_duration == "1"){
    $installments = 1;
} else {
    $installments = 1;
}

$assign_data = array(
    'field' => $id,
    'customer' => $customer,
    'contract_duration' => $duration,
    'payment_duration' => $payment_duration,
    'installment' => $installment_amount,
    'field_size' => $size,
    'purchase_cost' => $purchase_cost,
    'discount' => $discount,
    'total_due' => $total_due,
    'documentation' => $documentation,
    'rent_percentage' => $rent_percentage,
    'annual_rent' => $annual_rent,
    'start_date' => $start,
    'assigned_by' => $user,
    'assigned_date' => $date
);

require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/update.php";
include "../classes/inserts.php";

$get_details = new selects();
$update = new Update_table;
 // get field info
    $fds = $get_details->fetch_details_cond('fields','field_id', $id);
    foreach($fds as $fd){
        $field_name = $fd->field_name;
        $total_size = $fd->field_size;
        $location = $fd->location;
    }
//get total assigned field
$ass = $get_details->fetch_sum_single('assigned_fields', 'field_size', 'field', $id);
if(is_array($ass)){
    foreach($ass as $as){
        $total_assigned = $as->total;
    }
}else{
    $total_assigned = 0;
}
if($total_assigned < $total_size){
// update field to mark customer ownership
// $update->update('fields', 'customer', 'field_id', $customer, $id);

// if($update){
    // record field assignment
    $add_data = new add_data('assigned_fields', $assign_data);
    $add_data->create_data();

    // get last inserted assigned_id
    $ids = $get_details->fetch_lastInserted('assigned_fields', 'assigned_id');
    $assigned_id = $ids->assigned_id;

    // get customer details
    $cus = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
    foreach($cus as $cu){
        $client = $cu->customer;
        $customer_email = $cu->customer_email;
    }

    // generate purchase payment schedule
    $start_date = new DateTime($start);
    for($i = 1; $i <= $installments; $i++){
        // ✅ make the first installment = start date
        $due_date = clone $start_date;
        if($i > 1){
            $due_date->modify('+' . ($i - 1) . ' month');
        }

        $repayment_data = array(
            'field' => $id,
            'assigned_id' => $assigned_id,
            'customer' => $customer,
            'due_date' => $due_date->format('Y-m-d'),
            'amount_due' => $installment_amount,
            'store' => $farm,
            'posted_by' => $user,
            'post_date' => $date
        );

        $insert_repayment = new add_data('field_payment_schedule', $repayment_data);
        $insert_repayment->create_data();
    }

    // get first & last repayment dates
    $first_ids = $get_details->fetch_lastInsertedConAsc('field_payment_schedule', 'due_date', 'assigned_id', $assigned_id);
    foreach($first_ids as $first_id){
        $first_repayment_date = $first_id->due_date;
    }

    $last_ids = $get_details->fetch_lastInsertedCon('field_payment_schedule', 'due_date', 'assigned_id', $assigned_id);
    foreach($last_ids as $last_id){
        $last_repayment_date = $last_id->due_date;
    }

    // update assigned_fields with active status
    $update = new Update_table();
    $update->update_double('assigned_fields', 'contract_status', 1, 'due_date', $last_repayment_date, 'assigned_id', $assigned_id);

    // formatting
    $install_amount_fmt = number_format($installment_amount, 2);
    $purchase_fmt = number_format($purchase_cost, 2);
    $discount_fmt = number_format($discount, 2);
    $due_fmt = number_format($total_due, 2);
    $doc_fmt = number_format($documentation, 2);
    $annual_rent_fmt = number_format($annual_rent, 2);
    $sqm = $size * 500;
    // build purchase message
    $message = "
    <p>Dear $client,</p>
    <p>Congratulations! Your <strong>field purchase contract</strong> has been successfully activated.</p>

    <h3 style='color:green;'>Purchase Details:</h3>
    <ul>
        <li><strong>Field:</strong> $field_name</li>
        <li><strong>Location:</strong> $location</li>
        <li><strong>Size:</strong> $size Plot ($sqm sqm)</li>
        <li><strong>Purchase Cost:</strong> NGN$purchase_fmt</li>
        <li><strong>Discount applied:</strong> NGN$discount_fmt</li>
        <li><strong>Total Due:</strong> NGN$due_fmt</li>
        <li><strong>Documentation Fee:</strong> NGN$doc_fmt</li>
        <li><strong>Contract Duration:</strong> $duration year(s)</li>
        <li><strong>Annual Rent/Return:</strong> NGN$annual_rent_fmt ($rent_percentage%)</li>
        
        <li><strong>Due Date:</strong> $last_repayment_date</li>
    </ul>

    <p>Once payment is completed, your contract will be marked as <strong>fully purchased</strong> and you will begin to receive your <strong>annual rent/returns</strong> according to the agreed rate and contract duration.</p>

    <p>You can log in to your <strong><a href='https://davidorlah.dorthprosuite.com/client_portal'>Customer Portal</a></strong> anytime to track your payments, field details, and rent status.</p>

    <br>
    <p>Thank you for investing with <strong>Davidorlah Farms</strong>.</p>
    <p>Warm regards,<br>
    <strong>Management</strong><br>
    Davidorlah Nigeria Limited</p>";

    // notification
    $notif_data = array(
        'client' => $customer,
        'subject' => 'Your Field Purchase Contract is Active',
        'message' => 'Dear '.$client.', your field ('.$field_name.' - '.$size.' Plot) located at '.$location.' has been successfully assigned for purchase. Once installments are completed, you will start receiving annual returns of ₦'.$annual_rent_fmt.' ('.$rent_percentage.'%) for '.$duration.' year(s).',
        'post_date' => $date,
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
    $from_name = "Davidorlah Nigeria Ltd";
    $subj = 'Your Field Purchase Contract is Active';
    $msg = "<div>$message</div>";

    smtpmailer($to, $from, $from_name, $subj, $msg);

    echo "<div class='success'><p>Field purchase successfully recorded and contract activated! <i class='fas fa-thumbs-up'></i></p></div>";
}else{
     echo "<div class='success'><p style='background:red'>Plots exchausted for $field_name! Kindly assign another Land to the customer <i class='fas fa-thumbs-down'></i></p></div>";
     echo "<script>alert('Plots exchausted for $field_name! Kindly assign another Land to the customer');</script>";
}
}else{
    echo "Your ession has expired. please login to continue";
    header("Location: ../index.php");
}
?>
