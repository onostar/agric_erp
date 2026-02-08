<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        $user = $_SESSION['user_id'];
        $subject = htmlspecialchars(stripslashes($_POST['subject']));
        $message = htmlspecialchars(stripslashes($_POST['message']));
        $company = $_SESSION['company'];
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        require "../PHPMailer/PHPMailerAutoload.php";
        require "../PHPMailer/class.phpmailer.php";
        require "../PHPMailer/class.smtp.php";
       
        $data = array(
            'subject' => $subject,
            'message' => $message,
            'sent_by' => $user,
            'store' => $store,
            'date_sent' => date("Y-m-d H:i:s")
        );
        $send_mail = new add_data('sent_mails', $data);
        $send_mail->create_data();
        if($send_mail){
            /* send mails to customer */
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
            //get all customers
            $get_customers = new selects();
            $rows = $get_customers->fetch_details('customers');
            if(is_array($rows)){
                foreach($rows as $row){
                    //send email to each customer
                    $customer_email = $row->customer_email;
                    $customer_name = $row->customer;
                    $email_message = "
                    <p>Dear $customer_name,</p>
                    <p>$message</p>
                    <br><p><br><strong>Management</strong><br>Davidorlah Nigeria Limited</p>";
                    //send email logic would go here
                    
                    
                    $to = $customer_email;
                    $from = 'info@davidorlahfarms.com';
                    $from_name = "$company";
                    $name = "$company";
                    $subj = "$subject";
                    $msg = "<div>$email_message</div>";
                    
                    $error=smtpmailer($to, $from, $name ,$subj, $msg);

                }
            }
                echo "<div class='success'><p>Bulk email sent successfully <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }else{
        echo "<p class='error'>Session expired. Please login again</p>";
        header("Location: ../index.php");
    }