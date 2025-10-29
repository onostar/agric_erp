<?php
session_start();
date_default_timezone_set("Africa/Lagos");

$user = $_SESSION['user_id'];
$farm = $_SESSION['store_id'];
$date = date("Y-m-d H:i:s");

$id = strtoupper(htmlspecialchars(stripslashes($_POST['field_id'])));
$customer = htmlspecialchars(stripslashes($_POST['customer']));
$duration = htmlspecialchars(stripslashes($_POST['duration']));
$frequency = htmlspecialchars(stripslashes($_POST['frequency']));
$rent = htmlspecialchars(stripslashes($_POST['rent']));
$repayment = htmlspecialchars(stripslashes($_POST['repayment']));
$start = htmlspecialchars(stripslashes($_POST['start_date']));

 // determine total installments based on duration (in years)
    if($frequency == "Weekly"){
        $installments = $duration * 52;
    } elseif($frequency == "Monthly"){
        $installments = $duration * 12;
    } elseif($frequency == "Yearly"){
        $installments = $duration;
    } else {
        $installments = 1;
    }

    // installment amount
    $installment_amount = $repayment / $installments;
$assign_data = array(
    'field' => $id,
    'customer' => $customer,
    'duration' => $duration,
    'installment' => $installment_amount,
    'frequency' => $frequency,
    'rent' => $rent,
    'total_repayment' => $repayment,
    'start_date' => $start,
    'assigned_by' => $user,
    'assigned_date' => $date
);

require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";

// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/update.php";
include "../classes/inserts.php";

$get_details = new selects();

// update field to mark customer ownership
$update = new Update_table;
$update->update('fields', 'customer', 'field_id', $customer, $id);

if($update){
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

    //get fieldname
    $fds = $get_details->fetch_details_cond('fields','field_id', $id);
    foreach($fds as $fd){
        $field_name = $fd->field_name;
        $size = $fd->field_size;
        $location = $fd->location;
    }
   

    // generate rent repayment schedule
    $start_date = new DateTime($start);
    for($i = 1; $i <= $installments; $i++){
        $due_date = clone $start_date;
        if($frequency == "Weekly") {
            $due_date->modify("+$i week");
        } elseif ($frequency == "Monthly") {
            $due_date->modify("+$i month");
        } elseif ($frequency == "Yearly") {
            $due_date->modify("+$i year");
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

        $insert_repayment = new add_data('rent_schedule', $repayment_data);
        $insert_repayment->create_data();
    }

    // get first & last repayment dates
    $first_ids = $get_details->fetch_lastInsertedConAsc('rent_schedule', 'due_date', 'assigned_id', $assigned_id);
    foreach($first_ids as $first_id){
        $first_repayment_date = $first_id->due_date;
    }

    $last_ids = $get_details->fetch_lastInsertedCon('rent_schedule', 'due_date', 'assigned_id', $assigned_id);
    foreach($last_ids as $last_id){
        $last_repayment_date = $last_id->due_date;
    }

    // update assigned_fields with contract active status
    $update = new Update_table();
    $update->update_double('assigned_fields', 'contract_status', 1, 'due_date', $last_repayment_date, 'assigned_id', $assigned_id);

    $install_amount = number_format($installment_amount, 2);
    $rent_fmt = number_format($rent, 2);
    $total = number_format($repayment, 2);

    // build notification message
    $message = "
    <p>Dear $client,</p>
    <p>We are pleased to inform you that your field has been successfully assigned and activated under our rental program.</p>

    <h3 style='color:green;'>Field Rent Details:</h3>
    <ul>
        <li><strong>Field:</strong> $field_name</li>
        <li><strong>Location:</strong> $location</li>
        <li><strong>Size:</strong> $size Hec.</li>
        <li><strong>Contract Duration:</strong> $duration year(s)</li>
        <li><strong>Payment Frequency:</strong> $frequency</li>
        <li><strong>Total Rent:</strong> ₦$total</li>
        <li><strong>Installment Amount:</strong> ₦$install_amount</li>
        <li><strong>First Repayment Date:</strong> $first_repayment_date</li>
        <li><strong>Final Repayment Date:</strong> $last_repayment_date</li>
    </ul>

    <p>Please ensure timely payments according to your schedule to keep your contract active.</p>
    <p>You can log in to your client portal to view your field details, rent schedule, and payment history.</p>

    <br><p>Thank you for choosing <strong>Davidorlah Farms</strong>.</p>
    <p>Warm regards,<br>
    <strong>Farm Management Team</strong><br>
    Onostar Media</p>";

    // insert into notifications table
    $notif_data = array(
        'client' => $customer,
        'subject' => 'Your Field Has Been Assigned',
        'message' => 'Dear '.$client.', your field ('.$field_name.' => '.$size.' Hectares) located at '.$location.' has been successfully assigned under our rental program. Please log in to your client portal to view your rent schedule and payment dates.',
        'post_date' => $date,
    );

    $add_data = new add_data('notifications', $notif_data);
    $add_data->create_data();

    /* send mails to customer */
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
        $mail->From="admin@dorthprosuite.com";
        $mail->FromName=$from_name;
        $mail->Sender=$from;
        $mail->AddReplyTo($from, $from_name);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AddAddress($to);
        // $mail->AddAddress('onostarmedia@gmail.com');

        if(!$mail->Send()){
            return "Failed to send mail";
        } else {
            return "Message Sent Successfully";
        }
    }

    $to = $customer_email;
    $from = 'admin@dorthprosuite.com';
    $from_name = "Davidorlah Farms";
    $subj = 'Your Field Assignment is Successful';
    $msg = "<div>$message</div>";

    smtpmailer($to, $from, $from_name, $subj, $msg);

    echo "<div class='success'><p>Field assigned to client successfully! <i class='fas fa-thumbs-up'></i></p></div>";
}
?>
