<?php

    if(isset($_GET['deposit_id'])){
        $deposit = $_GET['deposit_id'];
        $customer = $_GET['customer'];
        
        // instantiate class
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/delete.php";

        //update wallet balance
        //get deposit amount
        $get_deposit = new selects();
        $dep = $get_deposit->fetch_details_group('deposits', 'amount', 'deposit_id', $deposit);
        $amount = $dep->amount;
         //get customer details
         $get_balance = new selects();
         $bals = $get_balance->fetch_details_cond('customers', 'customer_id', $customer);
         foreach($bals as $bal){
             $old_balance = $bal->wallet_balance;
             $old_debt = $bal->amount_due;
         };
         
        $new_balance = $old_balance - $amount;

        $update_wallet = new Update_table();
        $update_wallet->update('customers', 'wallet_balance', 'customer_id', $new_balance, $customer);
        if($update_wallet){
            //delete deposit
            $delete_deposit = new deletes();
            $delete_deposit->delete_item('deposits', 'deposit_id', $deposit);
    ?>
        
<?php
    echo "<div class='success'><p>Deposit reversed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    
}