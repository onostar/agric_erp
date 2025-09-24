<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $vendor = htmlspecialchars(stripslashes($_POST['vendor']));
        $amount = htmlspecialchars(stripslashes($_POST['amount']));
        $contra = htmlspecialchars(stripslashes($_POST['contra']));
        $details = "Vendor Payment";
        $trans_date = htmlspecialchars(stripslashes($_POST['trans_date']));
        $date = date("Y-m-d H:i:s");
        $todays_date = date("dmyh");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "VP".$store.$todays_date.$ran_num.$user;
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/update.php";
 //generate transaction number
        //get current date
        $todays_date = date("dmyhis");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $trx_num = "TR".$ran_num.$todays_date;
        //get vendor details
        $get_customer = new selects();
        $rows = $get_customer->fetch_details_cond('vendors', 'vendor_id', $vendor);
        foreach($rows as $row){
            $name = $row->vendor;
            $wallet = $row->balance;
            $vendor_ledger = $row->account_no;
        }
        //get ledger type
        $get_type = new selects();
        $types = $get_type->fetch_details_cond('ledgers', 'acn', $vendor_ledger);
        foreach($types as $type){
            $vendor_type = $type->account_group;
            $vendor_group = $type->sub_group;
            $vendor_class = $type->class;

        }
        //insert into purchase payment
        $data = array(
            'vendor' => $vendor,
            'invoice' => $invoice,
            'amount_paid' => $amount,
            'payment_mode' => 'Deposit',
            'posted_by' => $user,
            'store' => $store,
            'trans_date' => $trans_date,
            'post_date' => $date,
            'trx_number' => $trx_num,
        );
        $add_payment = new add_data('purchase_payments', $data);
        $add_payment->create_data();
        //check if wallet has money
        if($add_payment){
            //add funds to vendor balance
            /* $new_debt = intval($wallet) - intval($amount);
            $update_debt = new Update_table();
            $update_debt->update('vendors', 'balance', 'vendor_id', $new_debt, $vendor); */
            //insert into transaction table
            //get inventory legder id
            $get_inv = new selects();
            $invs = $get_inv->fetch_details_cond('ledgers', 'ledger_id', $contra);
            foreach($invs as $inv){
                $inventory_ledger = $inv->acn;
                $inventory_type = $inv->account_group;
                $inventory_group = $inv->sub_group;
                $inventory_class = $inv->class;
            }
            $debit_data = array(
                'account' => $vendor_ledger,
                'account_type' => $vendor_type,
                'sub_group' => $vendor_group,
                'class' => $vendor_class,
                'debit' => $amount,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'details' => $details,
                'trans_date' => $trans_date
            );
            $credit_data = array(
                'account' => $inventory_ledger,
                'account_type' => $inventory_type,
                'sub_group' => $inventory_group,
                'class' => $inventory_class,
                'credit' => $amount,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'details' => $details,
                'trans_date' => $trans_date
            );
            //add debit
            $add_debit = new add_data('transactions', $debit_data);
            $add_debit->create_data();      
            //add credit
            $add_credit = new add_data('transactions', $credit_data);
            $add_credit->create_data();
        // if($update_debt){
        //cash flow data
        $flow_data = array(
            'account' => $inventory_ledger,
            'details' => 'inventory purchase',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'outflow',
            'activity' => 'operating',
            'post_date' => $date,
            'posted_by' => $user
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
             echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        } */
    }