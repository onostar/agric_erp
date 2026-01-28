<?php
session_start();
date_default_timezone_set("Africa/Lagos");
$store = $_SESSION['store_id'];
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
$documentation_paid = htmlspecialchars(stripslashes($_POST['documentation_paid']));
$rent_percentage = htmlspecialchars(stripslashes($_POST['rent_percentage']));
$annual_rent = htmlspecialchars(stripslashes($_POST['annual_rent']));
$start = htmlspecialchars(stripslashes($_POST['start_date']));
$amount_paid = htmlspecialchars(stripslashes($_POST['amount_paid']));
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
//generate trx.number
$todays_date = date("dmyhis");
$ran_num ="";
for($i = 0; $i < 3; $i++){
    $random_num = random_int(0, 9);
    $ran_num .= $random_num;
}
$trx_num = "TR".$ran_num.$todays_date.$user;
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
    'trx_number' => $trx_num,
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
    $ids = $get_details->fetch_lastInsertedCon('assigned_fields', 'assigned_id', 'trx_number', $trx_num);
    foreach($ids as $idss){
        $assigned_id = $idss->assigned_id;
    }

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
        $schedule = $first_id->repayment_id;
    }

    $last_ids = $get_details->fetch_lastInsertedCon('field_payment_schedule', 'due_date', 'assigned_id', $assigned_id);
    foreach($last_ids as $last_id){
        $last_repayment_date = $last_id->due_date;
    }

    // update assigned_fields with active status
    $update = new Update_table();
    $update->update_double('assigned_fields', 'contract_status', 1, 'due_date', $last_repayment_date, 'assigned_id', $assigned_id);
    $balance = $total_due - $amount_paid;
    //check if payment was made
    if($amount_paid > 0){
        $trans_type = "Field purchase payment";
        //insert into deposits;
        
        $data = array(
            'posted_by' => $user,
            'customer' => $customer,
            'payment_mode' => 'Cash',
            'amount' => $amount_paid,
            'details' => 'Field purchase payment',
            'invoice' => $trx_num,
            'bank' => 0,
            'trx_type' => $trans_type,
            'trans_date' => $start,
            'post_date' => $date,
            'trx_number' => $trx_num,
            'store' => $store
        );
        $add_data = new add_data('deposits', $data);
        $add_data->create_data();
         //insert into customer trails
        $trail_data = array(
            'customer' => $customer,
            'store' => $store,
            'description' => $trans_type,
            'amount' => $amount_paid,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'post_date' => $date
        );
        $add_trail = new add_data('customer_trail', $trail_data);
        $add_trail->create_data();
        //insert into fieldpayments
        $repayment_data = array(
            'customer' => $customer,
            'store' => $store,
            'loan' => $assigned_id,
            'amount' => $amount_paid,
            'schedule' => $schedule,
            /* 'interest' => $interest,
            'processing_fee' => $processing_fee, */
            'payment_mode' => 'Cash',
            'details' => 'Field Purchase Payment',
            'invoice' => $trx_num,
            'bank' => 0,
            'posted_by' => $user,
            'post_date' => $date,
            'trx_number' => $trx_num,
            'trx_date' => $start
            
        );
        $add_repayment = new add_data('field_payments', $repayment_data);
        $add_repayment->create_data();
        //update field payment schedule
        $update = new Update_table();
        if($balance <= 0){
            //update repayment schedule
            $update->update_double('field_payment_schedule', 'amount_paid', $amount_paid, 'payment_status', 1, 'repayment_id', $schedule);
        }else{
            //update repayment schedule
            $update->update('field_payment_schedule', 'amount_paid', 'repayment_id', $amount_paid, $schedule);
        }
        //generate rent schedule
        $installments = $duration; //yearly
            
        $rent_start_date = new DateTime($start);
        for($i = 1; $i <= $installments; $i++){
            $rent_due_date = clone $rent_start_date;$rent_due_date->modify("+$i year");
            
            $rent_data = array(
                'field' => $id,
                'assigned_id' => $assigned_id,
                'customer' => $customer,
                'due_date' => $rent_due_date->format('Y-m-d'),
                'amount_due' => $annual_rent,
                'store' => $store,
                'posted_by' => $user,
                'post_date' => $date
            );

            $insert_repayment = new add_data('rent_schedule', $rent_data);
            $insert_repayment->create_data();
            
        }

        //now update all rent schedule as paid forclients whose rent is passed 2025 december
        $update->updateRentPayment($assigned_id);
        //get all rent paid and insert into rent payment table
        $rts = $get_details->fetch_details_2cond('rent_schedule', 'assigned_id', 'payment_status', $assigned_id, 1);
        if(is_array($rts)){
            foreach($rts as $rt){
                $rt_data = array(
                    'customer' => $customer,
                    'loan' => $assigned_id,
                    'store' => $store,
                    'schedule' => $rt->repayment_id,
                    'trx_number' => $trx_num,
                    'invoice' => $trx_num,
                    'amount' => $annual_rent,
                    'trx_date' => $rt->due_date,
                    'posted_by'=> $user,
                    'post_date' => $date
                );
                $add_rent = new add_data('rent_payments', $rt_data);
                $add_rent->create_data();
            }
        }
        if($amount_paid <= $total_due){
            $update->update('assigned_fields', 'contract_status', 'assigned_id', 2, $assigned_id);
        }

        //check for documentation payment
        if($documentation_paid > 0){
            $doc_data = array(
                'assigned_id' => $assigned_id,
                'client' => $customer,
                'field' => $id,
                'amount' => $documentation_paid,
                'trx_date' => $date,
                'trx_number' => $trx_num,
                'invoice' => $trx_num,
                // 'payment_mode' => $mode,
                'bank' => 0,
                'post_date' => $date,
                'posted_by' => $user,
                'store' => $store
            );
            $add_doc = new add_data('documentation_fees', $doc_data);
            $add_doc->create_data();
            $doc_balance = $documentation - $documentation_paid;

            if($doc_balance <= 0){
                //documentation status
                $update = new Update_table();
                $update->update('assigned_fields', 'documentation_status', 'assigned_id', 1, $assigned_id);
            }
        }
        
    }
    // formatting
    $install_amount_fmt = number_format($installment_amount, 2);
    $purchase_fmt = number_format($purchase_cost, 2);
    $discount_fmt = number_format($discount, 2);
    $due_fmt = number_format($total_due, 2);
    $doc_fmt = number_format($documentation, 2);
    $annual_rent_fmt = number_format($annual_rent, 2);
    $balance_fmt = number_format($total_due - $amount_paid, 2);
    $paid_fmt = number_format($amount_paid, 2);
    $sqm = $size * 500;
    $doc_paid = number_format($documentation_paid, 2);
    $doc_bal = number_format($documentation - $documentation_paid, 2);
    // build purchase message
    if($balance <= 0){
        $message = "
        <p>Dear $client,</p>
        <p>Congratulations! Your <strong>field purchase contract</strong> has been successfully activated.</p>

        <h3 style='color:green;'>Purchase Details:</h3>
        <ul>
            <li><strong>Field:</strong> $field_name</li>
            <li><strong>Location:</strong> $location</li>
            <li><strong>Size:</strong> $size Plot ($sqm sqm)</li>
            <li><strong>Purchase Cost:</strong> NGN $purchase_fmt</li>
            <li><strong>Discount applied:</strong> NGN $discount_fmt</li>
            <li><strong>Total Due:</strong> NGN $due_fmt</li>
            <li><strong>Total Paid:</strong> NGN $paid_fmt</li>
            <li><strong>Documentation Fee:</strong> NGN $doc_fmt</li>
            <li><strong>Documentation Paid:</strong> NGN $doc_paid</li>
            <li><strong>Documentation Balance:</strong> NGN $doc_bal</li>
            <li><strong>Contract Duration:</strong> $duration year(s)</li>
            <li><strong>Annual Rent/Return:</strong> NGN $annual_rent_fmt ($rent_percentage%)</li>
            
            <li><strong>Due Date:</strong> $last_repayment_date</li>
        </ul>

        <p>You can log in to your <strong><a href='https://davidorlah.dorthprosuite.com/client_portal'>Customer Portal</a></strong> anytime to track your payments, field details, and rent status.</p>
        <p>Your Username : $customer_email<br>
        Default Password : 123</p>

        <p><strong>N.B: The system will prompt you to change your password from the default password</strong></p>
        <br>
        <p>Thank you for investing with <strong>Davidorlah NIgeria Limited</strong>.</p>
        <p>Warm regards,<br>
        <strong>Management</strong><br>
        Davidorlah Nigeria Limited</p>";
    }else{
         $message = "
        <p>Dear $client,</p>
        <p>Congratulations! Your <strong>field purchase contract</strong> has been successfully activated.</p>

        <h3 style='color:green;'>Purchase Details:</h3>
        <ul>
            <li><strong>Field:</strong> $field_name</li>
            <li><strong>Location:</strong> $location</li>
            <li><strong>Size:</strong> $size Plot ($sqm sqm)</li>
            <li><strong>Purchase Cost:</strong> NGN $purchase_fmt</li>
            <li><strong>Discount applied:</strong> NGN $discount_fmt</li>
            <li><strong>Total Due:</strong> NGN $due_fmt</li>
            <li><strong>Total Paid:</strong> NGN $paid_fmt</li>
            <li><strong>Balance:</strong> NGN $balance_fmt</li>
            
            <li><strong>Documentation Fee:</strong> NGN $doc_fmt</li>
            <li><strong>Documentation Paid:</strong> NGN $doc_paid</li>
            <li><strong>Documentation Balance:</strong> NGN $doc_bal</li>
            <li><strong>Contract Duration:</strong> $duration year(s)</li>
            <li><strong>Annual Rent/Return:</strong> NGN $annual_rent_fmt ($rent_percentage%)</li>
            
            <li><strong>Due Date:</strong> $last_repayment_date</li>
        </ul>

        <p>Once payment is completed, your contract will be marked as <strong>fully purchased</strong> and you will begin to receive your <strong>annual rent/returns</strong> according to the agreed rate and contract duration.</p>

        <p>You can log in to your <strong><a href='https://davidorlah.dorthprosuite.com/client_portal'>Customer Portal</a></strong> anytime to track your payments, field details, and rent status.</p>
        <p>Your Username : $customer_email<br>
        Default Password : 123</p>

        <p><strong>N.B: The system will prompt you to change your password from the default password</strong></p>
        <br>
        <p>Thank you for investing with <strong>Davidorlah Nigeria Limited</strong>.</p>
        <p>Warm regards,<br>
        <strong>Management</strong><br>
        Davidorlah Nigeria Limited</p>";
    }

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
    $from_name = "Davidorlah Farms";
    $subj = 'Your Field Purchase Contract is Active';
    $msg = "<div>$message</div>";

    smtpmailer($to, $from, $from_name, $subj, $msg);

    echo "<div class='success'><p>Field purchase successfully recorded and contract activated! <i class='fas fa-thumbs-up'></i></p></div>";
}else{
     echo "<div class='success'><p style='background:red'>Plots exchausted for $field_name! Kindly assign another Land to the customer <i class='fas fa-thumbs-down'></i></p></div>";
     echo "<script>alert('Plots exchausted for $field_name! Kindly assign another Land to the customer');</script>";
}
?>
