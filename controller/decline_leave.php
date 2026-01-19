<?php
session_start();
date_default_timezone_set("Africa/Lagos");

if(isset($_GET['id'])){
    $leave = $_GET['id'];
    $approved_by = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $company = $_SESSION['company'];

    // include classes
    include "../classes/dbh.php";
    include "../classes/update.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    require "../PHPMailer/PHPMailerAutoload.php";
    require "../PHPMailer/class.phpmailer.php";
    require "../PHPMailer/class.smtp.php";

    // update leave status
    $data = [
        'leave_status' => -1,
        'approved_at' => $date,
        'approved_by' => $approved_by
    ];

    // get staff details
    $get_details = new selects();
    $results = $get_details->fetch_details_cond('leaves', 'leaves_id', $leave);
    if(!$results){
        echo "<div class='error'><p>Leave record not found!</p></div>";
        exit;
    }

    foreach($results as $result){
        $staff_id = $result->employee;
        $start_date = $result->start_date;
        $end_date = $result->end_date;
        $total_days = $result->total_days;
        $type = $result->leave_type;
    }
    $start = date("jS F, Y", strtotime($start_date));
    $end = date("jS F, Y", strtotime($end_date));
    //get leave type
    $typ = $get_details->fetch_details_group('leave_types', 'leave_title', 'leave_id', $type);
    $leave_type = $typ->leave_title;
    $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff_id);
    foreach($rows as $row){
        $staff_email = $row->email_address;
        $employee = $row->last_name." ".$row->other_names;
    }

    // perform update
    $recall = new Update_table;
    $recall->updateAny('leaves', $data, 'leaves_id', $leave);

    if($recall){
        // email message for leave approval
        $email_message = "
            <p>Dear $employee,</p>
            <p>Your leave application has been <strong>declined</strong> by management.</p>
            <p><strong>Leave Details:</strong></p>
            <ul>
                <li>Leave Type: $leave_type</li>
                <li>Start Date: $start</li>
                <li>End Date: $end</li>
                <li>Total Days: $total_days</li>
            </ul>
            <p>Thank you for your dedication and contribution to $company.</p>
            <br><p>Best regards,<br><strong>$company HR Team</strong></p>
        ";

        // send email function
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

        $to = $staff_email;
        $from = 'info@davidorlahfarms.com';
        $from_name = $company;
        $subject = "Leave Request Declined";
        $msg = $email_message;

        $error = smtpmailer($to, $from, $from_name, $subject, $msg);
        echo "<div class='success'><p>Leave request declined successfully! <i class='fas fa-thumbs-down'></i></p></div>";
    }
}
?>
