<?php
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $trans_date = htmlspecialchars(stripslashes($_POST['exp_date']));
    $contra = htmlspecialchars(stripslashes($_POST['contra']));
    $ledger = htmlspecialchars(stripslashes($_POST['dr_ledger']));
    // $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $asset = htmlspecialchars(stripslashes($_POST['asset']));
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
    $full_year = date("Y", strtotime($trans_date));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get asset details
    $get_asset = new selects();
    $rows = $get_asset->fetch_details_cond('assets', 'asset_id', $asset);
    foreach($rows as $row){
        $cost = $row->cost;
        // $contra = $row->ledger;
        $salvage_value = $row->salvage_value;
        $useful_life = $row->useful_life;
        $old_dep_amount = $row->accum_dep;
        $asset_name = $row->asset;
        $quantity = $row->quantity;
    }
    //check if asset has been depreciated for the year
    $check_dep = new selects();
    $deps = $check_dep->check_dep($asset,$full_year);
    if($deps > 0){
        echo "<p style='color:red'>Depreciation already Posted for $asset_name for $full_year!</p>";
    }else{
    $dep_amount = ($cost - $salvage_value) / $useful_life;
    $accum_dep = $old_dep_amount + $dep_amount;
    $book_value = $cost - $accum_dep;
    $data = array(
        'posted_by' => $user,
        'trx_date' => $trans_date,
        'useful_life' => $useful_life,
        'asset' => $asset,
        'cost' => $cost,
        'quantity' => $quantity,
        'salvage_value' => $salvage_value,
        'dr_ledger' => $ledger,
        'contra_ledger' => $contra,
        'accum_dep' => $accum_dep,
        'book_value' => $book_value,
        'amount' => $dep_amount,
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
    //post depreciation
    $add_data = new add_data('depreciation', $data);
    $add_data->create_data();
    if($add_data){
        //update asset details
        $update_asset = new Update_table();
        $update_asset->update_double('assets', 'accum_dep', $accum_dep, 'book_value', $book_value, 'asset_id', $asset);

        //insert into transaction table
        $debit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'class' => $dr_class,
            'debit' => $dep_amount * $quantity,
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
            'credit' => $dep_amount * $quantity,
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
        
        echo "<p>Depreciation Posted for $asset_name successfully!</p>";
    }
}