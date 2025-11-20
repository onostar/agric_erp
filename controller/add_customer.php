<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $customer = strtoupper(htmlspecialchars(stripslashes($_POST['customer'])));
        $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
        $address = ucwords(htmlspecialchars(stripslashes(($_POST['address']))));
        $email_address = strtolower(htmlspecialchars(stripslashes($_POST['email'])));
        $type = htmlspecialchars(stripslashes(($_POST['customer_type'])));
        //check customer type and create user account
        if($type == "Landowner"){
            $password = 123;
        }else{
            $password = "";
        }
        $data = array(
            'customer' => $customer,
            'phone_numbers' => $phone,
            'customer_email' => $email_address,
            'customer_address' => $address,
            'customer_type' => $type,
            'user_password' => $password,
            'reg_date' => $date,
            'created_by' => $user
        );
        $ledger_data = array(
            'account_group' => 1,
            'sub_group' => 1,
            'class' => 4,
            'ledger' => $customer
        );
        // instantiate class
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/update.php";
        require "../PHPMailer/PHPMailerAutoload.php";
        require "../PHPMailer/class.phpmailer.php";
        require "../PHPMailer/class.smtp.php";

    //check if customer exists
    $check = new selects();
    $results = $check->fetch_count_cond('customers', 'customer', $customer);
    $results2 = $check->fetch_count_cond('customers', 'customer_email', $email_address);
    $results3 = $check->fetch_count_cond('customers', 'phone_numbers', $phone);
    //checkledger
    $ledg = $check->fetch_count_cond('ledgers', 'ledger', $customer);
    if($results > 0 || $results3 > 0){
        echo "<p class='exist'><span>$customer</span> already exists!</p>";
        exit;
    }elseif($results2 > 0){
        echo "<p class='exist'><span>$email_address</span> already taken, try another email address</p>";
        exit;
    }elseif($results3 > 0){
        echo "<p class='exist'>Phone number already exists iin our database, try another phone number</p>";
        exit;
    }elseif($ledg > 0){
        echo "<p class='exist'><span>$customer</span> already exists in ledger</p>";
    }else{
        //create customer
        $add_data = new add_data('customers', $data);
        $add_data->create_data();
        if($add_data){
            //get customer id
            $get_cust = new selects();
            $cus_id = $get_cust->fetch_lastInserted('customers', 'customer_id');
            $customer_id = $cus_id->customer_id;
            //add to account ledger
            //check if customer is in ledger
            
            
            $add_ledger = new add_data('ledgers', $ledger_data);
            $add_ledger->create_data();
            //update customer ledger no
            //first get ledger id from ledger table
            $get_last = new selects();
            $ids = $get_last->fetch_lastInserted('ledgers', 'ledger_id');
            $ledger_id = $ids->ledger_id;
            //update account number
            $acn = "10104".$ledger_id;
            $update_acn = new Update_table();
            $update_acn->update('ledgers', 'acn', 'ledger_id', $acn, $ledger_id);
            //now update
            $update = new Update_table();
            $update->update_double('customers', 'ledger_id', $ledger_id, 'acn', $acn, 'customer_id', $customer_id);
            if($type == "Landowner"){
                //mail message
                $message = "<p>Dear $customer,</p>
                <p>Welcome to <strong>Davidorlah Farms</strong>! Your customer profile has been successfully created, and you now have access to your personal customer portal where you can view your account details, monitor transactions, and stay updated on your activities.</p>

                <p>You can log in using the link below:</p>
                <p><a href='https://davidorlah.dorthprosuite.com/client_portal/' target='_blank'>
                Customer Portal Login
                </a></p>
                <br>
                <p><strong>Login Details:</strong><br>
                Username: $email_address<br>
                Password: 123<br>

                <p>If you have any questions or need support, feel free to contact us at any time.</p>

                <p>Thank you for choosing <strong>Davidorlah Farms</strong>.</p>

                <p>Warm regards,<br>
                <strong>Farm Management Team</strong><br>
                Davidorlah Nigeria Limited
                </p>";
                /* send mail */
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

                    if(!$mail->Send()){
                        return "Failed to send mail";
                    } else {
                        return "Message Sent Successfully";
                    }
                }

                $to = $email_address;
                $from = 'admin@dorthprosuite.com';
                $from_name = "Davidorlah Farms";
                $subj = 'Your Customer Portal Access Details - Davidorlah Farms';
                $msg = "<div>$message</div>";

                smtpmailer($to, $from, $from_name, $subj, $msg);  
            }
            echo "<p><span>$customer</span> ceated successfully!</p>";
        }
    }
}else{
    echo "Your session has expired. Please login to continue";
}