<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $assigned_id = htmlspecialchars(stripslashes($_POST['assigned_id']));
    $amount = htmlspecialchars(stripslashes(($_POST['amount'])));
    $remark = htmlspecialchars(stripslashes(($_POST['remark'])));
     $receipt = $_FILES['receipt']['name'];
    $receipt_size = $_FILES['receipt']['size'];
    $allowed_ext = array('png', 'jpg', 'jpeg', 'webp', 'pdf');
    /* get current file extention */
    $file_ext = explode('.', $receipt);
    $file_ext = strtolower(end($file_ext));
    $ran_num ="";
    for($i = 0; $i < 5; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
     // instantiate class
    require "../PHPMailer/PHPMailerAutoload.php";
    require "../PHPMailer/class.phpmailer.php";
    require "../PHPMailer/class.smtp.php";
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check = new selects();
    //get field details
    $field_details = $check->fetch_details_cond('assigned_fields', 'assigned_id', $assigned_id);
    foreach($field_details as $field_detail){
        $field = $field_detail->field;
    }
    //get field name
    $rows = $check->fetch_details_cond('fields', 'field_id', $field);
    foreach($rows as $row){
        $field_name = $row->field_name;
    }
    $data = array(
        'customer' => $customer,
        'assigned_id' => $assigned_id,
        'amount' => $amount,
        // 'receipt' => $receipt,
        'remark' => $remark,
        'upload_date' => $date,
        'field' => $field,
        'posted_by' => $user,
        'store' => $store
    );
   

    //check if there is an exisiting upload not approved
    $existing = $check->fetch_count_2cond('payment_evidence', 'assigned_id', $assigned_id, 'payment_status', '0');
    
    if($existing > 0){
        echo "<script>alert('You have an existing payment evidence pending approval. Please wait for approval before uploading another receipt.');</script>";
        echo "<p class='exist'>You have an existing payment evidence pending approval. Please wait for approval before uploading another receipt.</p>";
        
    }else{
        //add payment evidence
        if(in_array($file_ext, $allowed_ext)){
            if($receipt_size <= 500000){
                $add_data = new add_data('payment_evidence', $data);
                $add_data->create_data();
                if($add_data){
                    //add receipt evidence
                    //get id
                    $ids = $check->fetch_lastInserted('payment_evidence', 'payment_id');
                    $payment_id = $ids->payment_id;
                    
                    $foto = $ran_num."_".$payment_id.".".$file_ext;
                    $photo_folder = "../receipts/".$foto;
                    if($file_ext == "pdf"){
                        // Just move the PDF
                        move_uploaded_file($_FILES['receipt']['tmp_name'], $photo_folder);
                    }else{
                        // Compress the image before saving
                        function compressImage($source, $destination, $quality){
                            //get image info
                            $imgInfo = getimagesize($source);
                            $mime = $imgInfo['mime'];
                            //create new image from file
                            switch($mime){
                                case 'image/png':
                                    $image = imagecreatefrompng($source);
                                    imagejpeg($image, $destination, $quality);
                                    break;
                                case 'image/jpeg':
                                    $image = imagecreatefromjpeg($source);
                                    imagejpeg($image, $destination, $quality);
                                    break;
                                
                                case 'image/webp':
                                    $image = imagecreatefromwebp($source);
                                    imagejpeg($image, $destination, $quality);
                                    break;
                                default:
                                    $image = imagecreatefromjpeg($source);
                                    imagejpeg($image, $destination, $quality);
                            }
                            //return compressed image
                            return $destination;
                        }
                        $compress = compressImage($_FILES['receipt']['tmp_name'], $photo_folder, 70);
                    }
                    //update payment evidence with receipt
                    $update_foto = new Update_table();
                    $update_foto->update('payment_evidence', 'evidence', 'payment_id', $foto, $payment_id);
                    if($update_foto){
                        //send mail to admins
                        //get customer details
                        $cus = $check->fetch_details_cond('customers', 'customer_id', $customer);
                        foreach($cus as $cu){
                            $client = $cu->customer;
                            $customer_email = $cu->customer_email;
                        }
                        $amount_paid = number_format($amount, 2);
                        $message = "
                            <p>Dear Admin,</p>
                            <p>$client has uploaded a new payment evidence for field ID: $field_name.<br>
                            <strong>Amount Paid: </strong> ₦$amount_paid.<br>Please review and approve the payment at your earliest convenience.</p>
                            <p>Thank you.<br>
                            Davidorlah Nigeria Limited.</p>";
                        function smtpmailer($to, $from, $from_name, $subject, $body, $photo_folder){
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
                            $mail->AddAttachment($photo_folder);

                            if(!$mail->Send()){
                                return "Failed to send mail";
                            } else {
                                return "Message Sent Successfully";
                            }
                        }

                        $to = 'onostarmedia@gmail.com';
                        $from = 'admin@dorthprosuite.com';
                        $from_name = "Davidorlah Nigeria Limited";
                        $subj = "Payment Upload from $client";
                        $msg = "<div>$message</div>";

                        smtpmailer($to, $from, $from_name, $subj, $msg, $photo_folder);
                        echo "<div class='success'><p>Payment uploaded successfully!</p></div>";

                    }else{
                        echo "<p class='exist'>Failed to upload receipt</p>";
                    }
                }
            }else{
                echo "<p class='exist'>File too large</p>";
            }
        }else{
            echo "<p class='exist'>Your Image format is not supported</p>";

        } 
    }