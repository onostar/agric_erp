<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_POST['submit_link'])){
        $email_address = filter_input(INPUT_POST, 'username', FILTER_VALIDATE_EMAIL);
         // instantiate class
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/update.php";
        require "../PHPMailer/PHPMailerAutoload.php";
        require "../PHPMailer/class.phpmailer.php";
        require "../PHPMailer/class.smtp.php";
        //generate token
        function generateToken($length = 8) {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $token = '';
            for ($i = 0; $i < $length; $i++) {
                $token .= $characters[random_int(0, $charactersLength - 1)];
            }
            return $token;
        }

        $token = generateToken(8);

        $get_details = new selects();
        //check if email exists in data base
        $check = $get_details->fetch_count_cond('customers', 'customer_email', $email_address);
        if($check > 0){
            //get customer details
            $customer_details = $get_details->fetch_details_cond('customers', 'customer_email', $email_address);
            foreach($customer_details as $details){
                $customer_name = $details->customer;
            }
            $data = array(
                'token' => $token,
                'token_expires' => date("Y-m-d H:i:s", strtotime("+1 hour"))
            );
            //update token in database
            $update_token = new Update_table();
            $update_token->updateAny('customers', $data, 'customer_email', $email_address);

            // build  message
            $message = "
            <div style='font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #333;'>

                <p>Dear <strong>$customer_name</strong>,</p>

                <p>
                    We received a request to reset your account password. 
                    Please use the secure token below to proceed with your password reset:
                </p>

                <p style='font-size:18px; letter-spacing:2px; font-weight:bold; color:#0a7f42;'>
                    $token
                </p>

                <p>
                    Alternatively, you may click the button below to reset your password securely:
                </p>

                <p style='margin:20px 0;'>
                    <a href='https://davidorlah.dorthprosuite.com/client_portal/reset_password.php?token=$token' 
                    style='background-color:#0a7f42; 
                            color:#ffffff; 
                            padding:12px 20px; 
                            text-decoration:none; 
                            border-radius:6px; 
                            display:inline-block;
                            font-weight:bold;'>
                        Reset Password
                    </a>
                </p>

                <p>
                    This token will expire in <strong>1 hour</strong> for security reasons.
                </p>

                <p>
                    If you did not request a password reset, please ignore this email. 
                    Your account remains secure, and no changes will be made.
                </p>

                <hr style='border:none; border-top:1px solid #eee; margin:25px 0;'>

                <p>
                    Thank you for choosing <strong>Davidorlah Nigeria Limited</strong>.
                </p>

                <p>
                    Warm regards,<br>
                    <strong>Management Team</strong><br>
                    Davidorlah Nigeria Limited
                </p>

            </div>
            ";

            /* send mail */
            function smtpmailer($to, $from, $from_name, $subject, $body, $photo_folder){
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
                $mail->AddAttachment($photo_folder);

                if(!$mail->Send()){
                    return "Failed to send mail";
                } else {
                    return "Message Sent Successfully";
                }
            }

            $to = $email_address;
            $from = 'info@davidorlahfarms.com';
            $from_name = "Davidorlah Nigeria Limited";
            $subj = 'Password Reset Request';
            $msg = "<div>$message</div>";

            smtpmailer($to, $from, $from_name, $subj, $msg, $photo_folder);
            $_SESSION['success'] = "Password reset token sent to your email successfully";
            header("Location: ../client_portal/reset_password.php");
        }else{
            $_SESSION['error'] = "Email address not found";
            $_SESSION['email'] = $email_address;
            header("Location: ../client_portal/forgot_password.php");
        }

    }else{
        header("Location: ../client_portal/index.php");
    }