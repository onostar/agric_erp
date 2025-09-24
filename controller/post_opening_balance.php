<?php
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $trans_date = htmlspecialchars(stripslashes($_POST['exp_date']));
    // $contra = htmlspecialchars(stripslashes($_POST['contra']));
    $ledger = htmlspecialchars(stripslashes($_POST['bal_ledger']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $trans_type = htmlspecialchars(stripslashes($_POST['trans_type']));
    $details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
    $date = date("Y-m-d H:i:s");
     //generate transaction number
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    $trx_num = "TR".$ran_num.$todays_date;
   
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
   
    $data = array(
        'posted_by' => $user,
        'trans_date' => $trans_date,
        'trans_type' => $trans_type,
        'store' => $store,
        'ledger' => $ledger,
        // 'contra_ledger' => $contra,
        'amount' => $amount,
        'details' => $details,
        'post_date' => $date,
        'trx_number' => $trx_num,
    );
    //get ledger account numbers and account type
    $get_exp = new selects();
    $exps = $get_exp->fetch_details_cond('ledgers', 'ledger_id', $ledger);
    foreach($exps as $exp){
        $main_ledger = $exp->acn;
        $main_type = $exp->account_group;
        $main_group = $exp->sub_group;
        $main_class = $exp->class;
    }
    //get contra ledger (retained earnings) account number
    /* $get_contra = new selects();
    $cons = $get_contra->fetch_details_cond('ledgers', 'ledger', 'EQUITY CAPITAL');
    foreach($cons as $con){
        $contra_ledger = $con->acn;
        $contra_type = $con->account_group;
        $contra_group = $con->sub_group;
    } */
    //post opening balance
    $add_data = new add_data('opening_balance', $data);
    $add_data->create_data();
    if($add_data){
        //insert into transaction table
        if($trans_type == "Debit"){
            $debit_data = array(
                'account' => $main_ledger,
                'account_type' => $main_type,
                'sub_group' => $main_group,
                'debit' => $amount,
                'details' => $details,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'trans_date' => $trans_date,
                'trx_status' => 1
            );
            /* $credit_data = array(
                'account' => $contra_ledger,
                'account_type' => $contra_type,
                'sub_group' => $contra_group,
                'credit' => $amount,
                'details' => $details,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num
            ); */
            //add debit
            $add_debit = new add_data('transactions', $debit_data);
            $add_debit->create_data(); 
            //add credit
           /*  $add_credit = new add_data('transactions', $credit_data);
            $add_credit->create_data();  */
            
        }else{
            $credit_data = array(
                'account' => $main_ledger,
                'account_type' => $main_type,
                'sub_group' => $main_group,
                'credit' => $amount,
                'details' => $details,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'trans_date' => $trans_date,
                'trx_status' => 1
            );
            /* $debit_data = array(
                'account' => $contra_ledger,
                'account_type' => $contra_type,
                'sub_group' => $contra_group,
                'debit' => $amount,
                'details' => $details,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num
            );
            //add debit
            $add_debit = new add_data('transactions', $debit_data);
            $add_debit->create_data();   */    
            //add credit
            $add_credit = new add_data('transactions', $credit_data);
            $add_credit->create_data();
           
        }
        //check if ledger is a customer or a vendor
        /* if($main_class == 4){
            //get customer details and add to balance
            $get_bal = new selects();
            $cust_bals = $get_bal->fetch_details_group('customers', 'wallet_balance', 'acn', $main_ledger);
            $customer_balance = $cust_bals->wallet_balance;
            if($trans_type == "Debit"){
                $new_balance = $customer_balance - $amount;
            }else{
                $new_balance = $customer_balance + $amount;
            }
            $update_balance = new Update_table();
            $update_balance->update('customers', 'wallet_balance', 'acn', $new_balance, $main_ledger);
        } */
        /* if($main_group == 3){
            //get vendor details and add to balance
            $get_bal = new selects();
            $cust_bals = $get_bal->fetch_details_group('vendors', 'balance', 'account_no', $main_ledger);
            $customer_balance = $cust_bals->balance;
            if($trans_type == "Debit"){
                $new_balance = $customer_balance - $amount;
            }else{
                $new_balance = $customer_balance + $amount;
            }
            $update_balance = new Update_table();
            $update_balance->update('vendors', 'balance', 'account_no', $new_balance, $main_ledger);
        } */
        echo "<p>Opening Balance Posted successfully!</p>";
    }
