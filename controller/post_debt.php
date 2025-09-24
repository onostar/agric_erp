<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $customer = htmlspecialchars(stripslashes($_POST['customer']));
        $amount = htmlspecialchars(stripslashes($_POST['amount']));
        $date = date("Y-m-d H:i:s");
        $posted_by = $_SESSION['user_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/inserts.php";
        
        //get customer details
        $get_customer = new selects();
        $rows = $get_customer->fetch_details_cond('customers', 'customer_id', $customer);
        foreach($rows as $row){
            $name = $row->customer;
            // $debt = $row->amount_due;
            $wallet = $row->wallet_balance;
        }
        //insert into outstanding
        $data = array(
            'customer' => $customer,
            'amount' => $amount,
            'post_date' => $date,
            'posted_by' => $posted_by
        );

        $add_debt = new add_data('outstanding', $data);
        $add_debt->create_data();
        //check if wallet has money
        
       /*  if($wallet > 0){
            //remove debt from wallet
            $new_wallet = $wallet - $amount;
            if($new_wallet < 0){
                $new_amount = intval($new_wallet) * (-1);
                //add excess amount to debt balance
                $new_debt = $new_amount;
                //update wallet debt balance
                $update_debt = new Update_table();
                $update_debt->update_double('customers', 'wallet_balance', 0, 'amount_due', $new_debt, 'customer_id', $customer);
                
            }else{
                //update debt and wallet balance
                $new_amount = $new_wallet;
                $update_debt = new Update_table();
                $update_debt->update_double('customers', 'amount_due', 0, 'wallet_balance', $new_amount, 'customer_id', $customer);
            }
        }else{
            //add funds to debt
            $new_debt = $amount + $debt;$update_debt = new Update_table();
            $update_debt->update('customers', 'amount_due', 'customer_id', $new_debt, $customer);
        } */
        $new_debt = intval($wallet) - intval($amount);
        $update_debt = new Update_table();
            $update_debt->update('customers', 'wallet_balance', 'customer_id', $new_debt, $customer);
        // if($update_debt){
             echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        } */
    // }