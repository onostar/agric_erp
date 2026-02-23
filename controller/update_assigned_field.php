<?php
session_start();
date_default_timezone_set("Africa/Lagos");
if(isset($_SESSION['user_id'])){
$user = $_SESSION['user_id'];
$farm = $_SESSION['store_id'];
$date = date("Y-m-d H:i:s");

$assigned_id = strtoupper(htmlspecialchars(stripslashes($_POST['assigned_id'])));
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
    'updated_by' => $user,
    'updated_at' => $date
);

require "../PHPMailer/PHPMailerAutoload.php";
require "../PHPMailer/class.phpmailer.php";
require "../PHPMailer/class.smtp.php";
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/update.php";
include "../classes/inserts.php";
include "../classes/delete.php";

$get_details = new selects();
$update = new Update_table;
$delete = new deletes();
//get previous assignment details
$rows = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $assigned_id);
foreach($rows as $row){
    $prev_field = $row->field;
    $prev_size = $row->field_size;
    $prev_customer = $row->customer;
    $prev_duration = $row->contract_duration;
    $prev_payment_duration = $row->payment_duration;
    $prev_purchase_cost = $row->purchase_cost;
    $prev_discount = $row->discount;
    $prev_total_due = $row->total_due;
    $prev_documentation = $row->documentation;
    $prev_rent_percentage = $row->rent_percentage;
    $prev_annual_rent = $row->annual_rent;
    $prev_start = $row->start_date;
}



// get previous field info
$fds = $get_details->fetch_details_cond('fields','field_id', $prev_field);
foreach($fds as $fd){
    $prev_field_name = $fd->field_name;
    $prev_location = $fd->location;
    $prev_total_size = $fd->field_size;
}


 // get field info
    $fds = $get_details->fetch_details_cond('fields','field_id', $id);
    foreach($fds as $fd){
        $field_name = $fd->field_name;
        $total_size = $fd->field_size;
        $location = $fd->location;
    }
//get total assigned field
if($prev_field == $id){
    $ass = $get_details->fetch_sum_single('assigned_fields', 'field_size', 'field', $prev_field);
    if(is_array($ass)){
        foreach($ass as $as){
            $assigned = $as->total;
        }
    }else{
        $assigned = 0;
    }
    $total_assigned = $assigned - $prev_size;
}else{
    $ass = $get_details->fetch_sum_single('assigned_fields', 'field_size', 'field', $id);
    if(is_array($ass)){
        foreach($ass as $as){
            $total_assigned = $as->total;
        }
    }else{
        $total_assigned = 0;
    }
}
if($total_assigned < $total_size){
    // record field assignment
    $update->updateAny('assigned_fields', $assign_data, 'assigned_id', $assigned_id);
    // $add_data->create_data();

    // get customer details
    $cus = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
    foreach($cus as $cu){
        $client = $cu->customer;
        $customer_email = $cu->customer_email;
    }
    //get previous customer info
    $cus = $get_details->fetch_details_cond('customers', 'customer_id', $prev_customer);
    foreach($cus as $cu){
        $prev_client = $cu->customer;
        $prev_customer_email = $cu->customer_email;
    }
    //delete previous payment schedule
    $delete->delete_item('field_payment_schedule', 'assigned_id', $assigned_id);
    
    // generate new purchase payment schedule
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
    //old purchase info formatting
    $prev_purchase_fmt = number_format($prev_purchase_cost, 2);
    $prev_discount_fmt = number_format($prev_discount, 2);
    $prev_due_fmt = number_format($prev_total_due, 2);
    $prev_doc_fmt = number_format($prev_documentation, 2);
    $prev_annual_rent_fmt = number_format($prev_annual_rent, 2);
    $prev_sqm = $prev_size * 500;
    if($customer != $prev_customer){
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
        <p>Thank you for investing with <strong>Davidorlah Nigeria Ltd</strong>.</p>
        <p>Warm regards,<br>
        <strong>Management</strong><br>
        Davidorlah Nigeria Limited</p>";

        
    }else{
        $changes = "";
        if($prev_field != $id){
            $changes .= "<li><strong>Field:</strong> $prev_field_name → $field_name</li>";
        }
        if($prev_size != $size){
            $changes .= "<li><strong>Size:</strong> $prev_size Plot → $size Plot</li>";
        }
        if($prev_purchase_cost != $purchase_cost){
            $changes .= "<li><strong>Purchase Cost:</strong> NGN$prev_purchase_fmt → NGN$purchase_fmt</li>";
        }
        if($prev_discount != $discount){
            $changes .= "<li><strong>Discount:</strong> NGN$prev_discount_fmt → NGN$discount_fmt</li>";
        }

        if($prev_total_due != $total_due){
            $changes .= "<li><strong>Total Due:</strong> NGN$prev_due_fmt → NGN$due_fmt</li>";
        }
        if($prev_duration != $duration){
            $changes .= "<li><strong>Contract Duration:</strong> $prev_duration year(s) → $duration year(s)</li>";
        }
        if($prev_payment_duration != $payment_duration){
            $changes .= "<li><strong>Payment Duration:</strong> $prev_payment_duration month(s) → $payment_duration month(s)</li>";
        }
        if($prev_rent_percentage != $rent_percentage){
            $changes .= "<li><strong>Rent Percentage:</strong> $prev_rent_percentage% → $rent_percentage%</li>";
        }
        if($prev_annual_rent != $annual_rent){
            $changes .= "<li><strong>Annual Rent:</strong> NGN$prev_annual_rent_fmt → NGN$annual_rent_fmt</li>";
        }
        if($prev_documentation != $documentation){
            $changes .= "<li><strong>Documentation Fee:</strong> NGN$prev_doc_fmt → NGN$doc_fmt</li>";
        }
        if($changes == ""){
            $change = "<p>No changes were made to the contract details.</p>";
        }else{
            $change = "<h3>Changes Made:</h3><ul>$changes</ul>";
        }
         // build purchase message
        $message = "
        <p>Dear $client,</p>
        <p>Your <strong>field purchase contract</strong> has been successfully updated with the new details below:</p>

        <h3 style='color:green;'>Updated Purchase Details:</h3>
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
        <div style='background:#f4f4f4; padding:10px; border-radius:5px; margin-bottom:20px;'>$change</div>
        <p>You can log in to your <strong><a href='https://davidorlah.dorthprosuite.com/client_portal'>Customer Portal</a></strong> anytime to track your payments, field details, and rent status.</p>

        <br>
        <p>Thank you for investing with <strong>Davidorlah Nigeria Ltd</strong>.</p>
        <p>Warm regards,<br>
        <strong>Management</strong><br>
        Davidorlah Nigeria Limited</p>";
    }
    if($customer != $prev_customer){
        $subject = "Your Field Purchase Contract is Active";
    }else{
        $subject = "Your Field Purchase Contract has been Updated";
    }
    // notification
    $notif_data = array(
        'client' => $customer,
        'subject' => $subject,
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
    $subj = $subject;
    $msg = "<div>$message</div>";

    smtpmailer($to, $from, $from_name, $subj, $msg);

    echo "<div class='success'><p>Field purchase updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
}else{
     echo "<div class='success'><p style='background:red'>Plots exchausted for $field_name! Kindly assign another Land to the customer <i class='fas fa-thumbs-down'></i></p></div>";
     echo "<script>alert('Plots exchausted for $field_name! Kindly assign another Land to the customer');</script>";
}
}else{
    echo "Your ession has expired. please login to continue";
    header("Location: ../index.php");
}
?>
