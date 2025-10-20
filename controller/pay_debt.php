<?php
date_default_timezone_set("Africa/Lagos");

    session_start();
    $posted_by = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];
    $detail = "Debt payment";
    if(isset($_GET['receipt'])){
        $customer = $_GET['customer'];
        $invoice = $_GET['receipt'];
        $amount = $_GET['amount_owed'];
        $mode = "Wallet";
        $date = date("Y-m-d H:i:s");
        //instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/update.php";
        
        //get wallet total balance
        $get_wallet = new selects();
        $bals = $get_wallet->fetch_details_group('customers', 'wallet_balance', 'customer_id', $customer);
        $wallet_balance = $bals->wallet_balance;

        //insert into other payment
        $data = array(
            'amount' => $amount,
            'payment_mode' => $mode,
            'posted_by' => $posted_by,
            'invoice' => $invoice,
            'customer' => $customer,
            'store' => $store,
            'post_date' => $date
        );
        $add_data = new add_data('other_payments', $data);
        $add_data->create_data();
        if($add_data){
            //insert into customer trails
            $cus_data = array(
                'customer' => $customer,
                'store' => $store,
                'description' => $detail,
                'invoice' => $invoice,
                'amount' => $amount,
                // 'trx_number' => $trx_num,
                'posted_by' => $posted_by,
                'post_date' => $date
            );
            $insert_credit = new add_data('customer_trail', $cus_data);
            //update debtor
            $update_debt = new Update_table();
            $update_debt->update('debtors', 'debt_status', 'invoice', 1, $invoice);
            if($update_debt){
                //update wallet balance
                /* $new_balance = $wallet_balance - $amount;
                $update_wallet = new Update_table();
                $update_wallet->update('customers', 'wallet_balance', 'customer_id', $new_balance, $customer); */

                echo "<div class='success'><p>Debt has been cleared successfully for invoice => $invoice! <i class='fas fa-thumbs-up'></i></p></div>";
            }
            
        }
        
    }
    