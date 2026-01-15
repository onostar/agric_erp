<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    $user = $_SESSION['user_id'];
    $customer = htmlspecialchars(stripslashes($_POST['customer_id']));
    $doc_type = strtoupper(htmlspecialchars(stripslashes($_POST['doc_type'])));
    $title = ucwords(htmlspecialchars(stripslashes($_POST['title'])));
    $photo = $_FILES['document_upload']['name'];
    // $photo_folder = "../id_cards/".$photo;
    $photo_size = $_FILES['document_upload']['size'];
    $allowed_ext = array('png', 'jpg', 'jpeg', 'webp', 'pdf', 'docx');
    //get current file extention
    $file_ext = explode('.', $photo);
    $file_ext = strtolower(end($file_ext));
    $ran_num ="";
    for($i = 0; $i < 5; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    
    $document = $ran_num."_".$customer.".".$file_ext;
    $photo_folder = "../documents/".$document;
    $data = array(
        'customer' => $customer,
        'doc_type' => $doc_type,
        'title' => $title,
        'document' => $document,
        'uploaded_by' => $user,
        'upload_date' => date("Y-m-d H:i:s")
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    require "../PHPMailer/PHPMailerAutoload.php";
    require "../PHPMailer/class.phpmailer.php";
    require "../PHPMailer/class.smtp.php";
    $get = new selects();
    //get customer details
    $cus = $get->fetch_details_cond('customers', 'customer_id', $customer);
    foreach($cus as $cu){
        $client = $cu->customer;
        $customer_email = $cu->customer_email;
    }
    //add document
    if(in_array($file_ext, $allowed_ext)){
        if($photo_size <= 5000000){
            // For images: compress and save
            if (in_array($file_ext, ['png', 'jpg', 'jpeg', 'webp'])) {
                function compressImage($source, $destination, $quality) {
                    $imgInfo = getimagesize($source);
                    $mime = $imgInfo['mime'];

                    switch($mime){
                        case 'image/png':
                            $image = imagecreatefrompng($source);
                            break;
                        case 'image/jpeg':
                            $image = imagecreatefromjpeg($source);
                            break;
                        case 'image/webp':
                            $image = imagecreatefromwebp($source);
                            break;
                        default:
                            return false;
                    }
                    return imagejpeg($image, $destination, $quality);
                }

                $compressed = compressImage($_FILES['document_upload']['tmp_name'], $photo_folder, 70);
                $upload_success = $compressed;
            } else {
                // For PDFs and DOCX, just move the file without compression
                $upload_success = move_uploaded_file($_FILES['document_upload']['tmp_name'], $photo_folder);
            }
            if($upload_success){
                //update document data
                $add_doc = new add_data('document_uploads', $data);
                $add_doc->create_data();
                if($add_doc){
                    // build  message
                    $message = "
                    <p>Dear $client,</p>

                    <p>
                    We are pleased to inform you that your <strong>$doc_type</strong> has been successfully uploaded to your account.
                    </p>
                    <p>Other details: $title</p>
                    <p>
                    You may log in to your <strong>
                    <a href='https://davidorlah.dorthprosuite.com/client_portal/' target='_blank'>
                    Investor Portal
                    </a>
                    </strong> to view and download this document at your convenience.
                    </p>

                    <p>
                    If you have any questions or require further assistance, please do not hesitate to contact our support team.
                    </p>

                    <br>

                    <p>
                    Thank you for trusting <strong>Davidorlah Nigeria Limited</strong> with your investment.
                    </p>

                    <p>
                    Warm regards,<br>
                    <strong>Investment Management Team</strong><br>
                    Davidorlah Nigeria Limited
                    </p>
                    ";

                    /* send mail */
                    function smtpmailer($to, $from, $from_name, $subject, $body, $photo_folder){
                        $mail = new PHPMailer();
                        $mail->IsSMTP();
                        $mail->SMTPAuth = true; 
                        $mail->SMTPSecure = 'ssl'; 
                        $mail->Host = 'www.davidorlahfarms.com';
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

                    $to = $customer_email;
                    $from = 'info@davidorlahfarms.com';
                    $from_name = "Davidorlah Nigeria Limited";
                    $subj = 'New Document Uploaded Successfully';
                    $msg = "<div>$message</div>";

                    smtpmailer($to, $from, $from_name, $subj, $msg, $photo_folder);
                    echo "<p><span>Document uploaded Successfully</p>";
                }else{
                    echo "<p class='exist'>Failed to upload Document</p>";
                }
            }else{
                echo "<p class='exist'>Failed to compress image</p>";
            }
        }else{
            echo "<p class='exist'>File too large</p>";
        }
    }else{
        echo "<p class='exist'>Your Document format is not supported</p>";

    }  
               
