<?php
date_default_timezone_set("Africa/Lagos");

    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $trx_date = htmlspecialchars(stripslashes($_POST['exp_date']));
    $contra = htmlspecialchars(stripslashes($_POST['contra']));
    $head = htmlspecialchars(stripslashes($_POST['exp_head']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
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
    $data = array(
        'posted_by' => $user,
        'expense_date' => $trx_date,
        'post_date' => $date,
        'expense_head' => $head,
        'contra' => $contra,
        'amount' => $amount,
        'details' => $details,
        'store' => $store,
        'trx_number' => $trx_num
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    //get expense ledger account numbers and account type
    /* $get_exp = new selects();
    $exp = $get_exp->fetch_details_group('expense_heads', 'acn', 'exp_head_id', $head); */
     //get account type
     $get_type = new selects();
     $expt = $get_type->fetch_details_cond('ledgers', 'ledger_id', $head);
     foreach($expt as $exp){
        $exp_type = $exp->account_group;
        $exp_group = $exp->sub_group;
        $exp_ledger = $exp->acn;
        $exp_class = $exp->class;


     }
    //get contra ledger account number
    $get_contra = new selects();
    $cons = $get_contra->fetch_details_cond('ledgers', 'ledger_id', $contra);
    foreach($cons as $con){
        $contra_ledger = $con->acn;
        $contra_type = $con->account_group;
        $contra_group = $con->sub_group;
        $contra_class = $con->class;
    }
    //post expense
    $add_data = new add_data('expenses', $data);
    $add_data->create_data();
    if($add_data){
        //insert into transaction table
        $debit_data = array(
            'account' => $exp_ledger,
            'account_type' => $exp_type,
            'sub_group' => $exp_group,
            'class' => $exp_class,
            'debit' => $amount,
            'details' => $details,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num, 
            'trans_date' => $trx_date

        );
        $credit_data = array(
            'account' => $contra_ledger,
            'account_type' => $contra_type,
            'sub_group' => $contra_group,
            'class' => $contra_class,
            'credit' => $amount,
            'details' => $details,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trx_date

        );
        //add debit
        $add_debit = new add_data('transactions', $debit_data);
        $add_debit->create_data();      
        //add credit
        $add_credit = new add_data('transactions', $credit_data);
        $add_credit->create_data(); 
        //cash flow data
        $flow_data = array(
            'account' => $contra_ledger,
            'details' => 'expense',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'outflow',
            'activity' => 'operating',
            'post_date' => $date,
            'posted_by' => $user
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
        echo "<p>Expense Posted successfully!</p>";
    }