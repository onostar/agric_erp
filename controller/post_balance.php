<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $customer = htmlspecialchars(stripslashes($_POST['vendor']));
        $amount = htmlspecialchars(stripslashes($_POST['amount']));
        

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";

        //get customer details
        $get_customer = new selects();
        $rows = $get_customer->fetch_details_cond('vendors', 'vendor_id', $customer);
        foreach($rows as $row){
            $name = $row->vendor;
            $debt = $row->balance;
        }

        
            //add funds to debt
            $new_debt = $amount + $debt;
            $update_debt = new Update_table();
            $update_debt->update('vendors', 'balance', 'vendor_id', $new_debt, $customer);
        
        
        // if($update_debt){
             echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        } */
    // }