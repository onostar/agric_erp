<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $receipt = htmlspecialchars(stripslashes($_POST['invoice']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $payroll = htmlspecialchars(stripslashes($_POST['payroll_month']));
    $bank = htmlspecialchars(stripslashes($_POST['bank']));
    $trans_date = htmlspecialchars(stripslashes($_POST['trans_date']));
    $details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
    $trans_type = "Salary Disbursement";
    // $type = "Deposit";
    $date = date("Y-m-d H:i:s");
    $company = $_SESSION['company'];
     //generate transaction number
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    $trx_num = "TR".$ran_num.$todays_date;
    $data = array(
        'posted_by' => $user,
        'payroll_date' => $payroll,
        'payment_mode' => $mode,
        'amount' => $amount,
        'details' => $details,
        'invoice' => $receipt,
        'bank' => $bank,
        'trans_date' => $trans_date,
        'post_date' => $date,
        'trx_number' => $trx_num,
        'store' => $store
    );
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    
    //post deposit
    $add_data = new add_data('salary_disbursement', $data);
    $add_data->create_data();
    if($add_data){
        $update = new Update_table();
        $update->update_salary($store, $payroll, $date,);
        $get_balance = new selects();
        
        //get salary account
        $get_type = new selects();
        //get salary ledger
        $sal = $get_type->fetch_details_cond('ledgers', 'ledger', 'SALARY AND WAGES');
        foreach($sal as $sa){
            $salary = $sa->acn;
        }
        $types = $get_type->fetch_details_cond('ledgers', 'acn', $salary);
        foreach($types as $type){
            $ledger_type = $type->account_group;
            $ledger_group = $type->sub_group;
            $ledger_class = $type->class;

        }
        //get contra ledger details
        if($mode == "Cash"){
            $ledger_name = "CASH ACCOUNT";
        }else{
            //get bank
            $get_bank = new selects();
            $bnk = $get_bank->fetch_details_group('banks', 'bank', 'bank_id', $bank);
            $ledger_name = $bnk->bank;
        }
        $get_inv = new selects();
        $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', $ledger_name);
        foreach($invs as $inv){
            $dr_ledger = $inv->acn;
            $dr_type = $inv->account_group;
            $dr_group = $inv->sub_group;
            $dr_class = $inv->class;
        }
        //cash or bank
        $credit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'class' => $dr_class,
            'details' => $trans_type,
            'credit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date,
            'store' => $store

        );
        //salary ledger
        $debit_data = array(
            'account' => $salary,
            'account_type' => $ledger_type,
            'sub_group' => $ledger_group,
            'class' => $ledger_class,
            'details' => $trans_type,
            'debit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date,
            'store' =>  $store

        );
        //add debit
        $add_debit = new add_data('transactions', $debit_data);
        $add_debit->create_data();      
        //add credit
        $add_credit = new add_data('transactions', $credit_data);
        $add_credit->create_data();
       
        //cash flow date
        $flow_data = array(
            'account' => $dr_ledger,
            'details' => $trans_type,
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
        
        echo "<div class='success'><p style=' margin:5px 50px'>Salary Disbursed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    // }
}