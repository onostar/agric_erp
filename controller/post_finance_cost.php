<?php
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $trans_date = htmlspecialchars(stripslashes($_POST['exp_date']));
    $contra = htmlspecialchars(stripslashes($_POST['contra']));
    $ledger = htmlspecialchars(stripslashes($_POST['financier']));
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
        'financier' => $ledger,
        'contra_ledger' => $contra,
        'amount' => $amount,
        'details' => $details,
        'post_date' => $date,
        'trx_number' => $trx_num
    );
    //get ledger account numbers and account type
    $get_exp = new selects();
    $exps = $get_exp->fetch_details_cond('ledgers', 'ledger_id', $ledger);
    foreach($exps as $exp){
        $dr_ledger = $exp->acn;
        $dr_type = $exp->account_group;
        $dr_group = $exp->sub_group;
        $dr_class = $exp->class;
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
    //post transaction
    $add_data = new add_data('finance_cost', $data);
    $add_data->create_data();
    if($add_data){
        //insert into transaction table
        
        $debit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'class' => $dr_class,
            'debit' => $amount,
            'details' => $details,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date

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
            'trans_date' => $trans_date

        );
        //add debit
        $add_debit = new add_data('transactions', $debit_data);
        $add_debit->create_data();      
        //add credit
        $add_credit = new add_data('transactions', $credit_data);
        $add_credit->create_data();
        if($trans_type == "Bank Charges"){
            $activity = "operating";
            $flow_details = "bank charges";
        }elseif($trans_type == "Tax"){
            $activity = "operating";
            $flow_details = "tax payments";
        }else{
            $activity = "financing";
            $flow_details = "finance cost";
        }
        //cash flow data
        $flow_data = array(
            'account' => $contra_ledger,
            'details' => $flow_details,
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'outflow',
            'activity' => $activity,
            'post_date' => $date,
            'posted_by' => $user
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
    
        
        echo "<p>Transaction Posted successfully!</p>";
    }
