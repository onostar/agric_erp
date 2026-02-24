<?php
    session_start();
    if(isset($_POST['submit_token'])){
        $token = htmlspecialchars(stripslashes($_POST['token']));

        // instantiate class
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";

        //get customer details
        $get = new selects();
        $customer_details = $get->fetch_details_cond('customers', 'token', $token);
        if(is_array($customer_details)){
            foreach($customer_details as $details){
                $customer_id = $details->customer_id;
                $token_expiry = $details->token_expires;
                $username = $details->customer_email;
            }
            if(strtotime(date("Y-m-d H:i:s")) > strtotime($token_expiry)){
                $_SESSION['error'] = "Token has expired! Please request a new password reset.";
                header("Location: ../client_portal/forgot_password.php");
            }else{
                //reset password
                $new_password = 123;
                $update = new Update_table();
                $update->update('customers', 'user_password', 'customer_id', $new_password, $customer_id);
                if($update){
                    $_SESSION['success'] = "Password reset successfully! Please enter your new password.";
                    $_SESSION['user'] = $username;
                    header("Location: ../client_portal/change_client_password.php");
                    
                }
                
            }

        }else{
            $_SESSION['error'] = "Invalid token! Please try again.";
            header("Location: ../client_portal/reset_password.php");
            exit();
        }

         
    }else{
        $_SESSION['error'] = "Please enter the token sent to your email address.";
        header("Location: ../client_portal/reset_password.php");
        exit();
    }