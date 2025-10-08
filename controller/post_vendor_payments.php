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
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('vendors', 'vendor_id', $vendor);
        foreach($rows as $row){
            $name = $row->vendor;
            $wallet = $row->balance;
            $vendor_ledger = $row->account_no;
        }
        //get ledger type
        $types = $get_details->fetch_details_cond('ledgers', 'acn', $vendor_ledger);
        foreach($types as $type){
            $vendor_type = $type->account_group;
            $vendor_group = $type->sub_group;
            $vendor_class = $type->class;

        }
        //get contra legder details
        $invs = $get_details->fetch_details_cond('ledgers', 'ledger_id', $contra);
        foreach($invs as $inv){
            $contra_ledger = $inv->acn;
            $contra_type = $inv->account_group;
            $contra_group = $inv->sub_group;
            $contra_class = $inv->class;
            $contra_name = $inv->ledger;
        }
        if($contra_name == "CASH ACCOUNT"){
            $mode = "Cash";
        }else{
            $mode = "Bank";
        }
        //insert into purchase payment
        /* $data = array(
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
        $add_payment->create_data(); */
        //insert into vendor payment
        $vendor_data = array(
            'vendor' => $vendor,
            'amount' => $amount,
            'contra' => $contra,
            'payment_mode' => $mode,
            'posted_by' => $user,
            'store' => $store,
            'post_date' => $date,
            'trans_date' => $trans_date,
            'trx_number' => $trx_num
        );
        $add_payment = new add_data('vendor_payments', $vendor_data);
        $add_payment->create_data();
        //check if wallet has money
        if($add_payment){
            //insert into transaction table
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
                'trans_date' => $trans_date,
                'store' => $store
            );
            $credit_data = array(
                'account' => $contra_ledger,
                'account_type' => $contra_type,
                'sub_group' => $contra_group,
                'class' => $contra_class,
                'credit' => $amount,
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'details' => $details,
                'trans_date' => $trans_date,
                'store' => $store
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
            'account' => $contra_ledger,
            'destination' => $vendor_ledger,
            'details' => 'inventory purchase',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'outflow',
            'activity' => 'operating',
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
             echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        } */
    }