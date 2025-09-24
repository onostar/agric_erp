<?php
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $trans_date = htmlspecialchars(stripslashes($_POST['exp_date']));
    $contra = htmlspecialchars(stripslashes($_POST['contra']));
    $ledger = htmlspecialchars(stripslashes($_POST['exp_head']));
    $asset = htmlspecialchars(stripslashes($_POST['asset']));
    // $amount = htmlspecialchars(stripslashes($_POST['amount']));
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
    //get asset cost
    $get_asset = new selects();
    $csts = $get_asset->fetch_details_cond('assets', 'asset_id', $asset);
    foreach($csts as $cst){
        $amount = $cst->cost;
        $quantity = $cst->quantity;
    }
    $total_amount = $amount * $quantity;
    $data = array(
        'posted_by' => $user,
        'trans_date' => $trans_date,
        'asset' => $asset,
        'asset_ledger' => $ledger,
        'contra_ledger' => $contra,
        'amount' => $amount,
        'total_amount' => $total_amount,
        'quantity' => $quantity,
        'details' => $details,
        'store' => $store,
        'post_date' => $date,
        'trx_number' => $trx_num
    );
    //get ledger account numbers and account type
    $get_exp = new selects();
    $exps = $get_exp->fetch_details_cond('ledgers', 'ledger_id', $ledger);
    foreach($exps as $exp){
        $asset_ledger = $exp->acn;
        $asset_type = $exp->account_group;
        $asset_group = $exp->sub_group;
        $asset_class = $exp->class;
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
    $add_data = new add_data('asset_postings', $data);
    $add_data->create_data();
    if($add_data){
        //upate asset status
        $update_asset = new Update_table();
        $update_asset->update('assets', 'asset_status', 'asset_id', 2, $asset);
        //insert into transaction table
        $debit_data = array(
            'account' => $asset_ledger,
            'account_type' => $asset_type,
            'sub_group' => $asset_group,
            'class' => $asset_class,
            'debit' => $total_amount,
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
            'details' => $details,
            'credit' => $total_amount,
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
        //cash flow data
        $flow_data = array(
            'account' => $contra_ledger,
            'details' => 'asset purchase',
            'trx_number' => $trx_num,
            'amount' => $total_amount,
            'trans_type' => 'outflow',
            'activity' => 'investing',
            'post_date' => $date,
            'posted_by' => $user
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
        echo "<p>Asset Posted successfully!</p>";
    }