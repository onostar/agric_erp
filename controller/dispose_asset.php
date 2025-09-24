<?php
date_default_timezone_set("Africa/Lagos");

    session_start();
    $user = $_SESSION['user_id'];
    $id = htmlspecialchars(stripslashes($_POST['asset_id']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $contra = htmlspecialchars(stripslashes($_POST['contra']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $reason = htmlspecialchars(stripslashes($_POST['reason']));
    $date = date("Y-m-d H:i:s");
    
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

   
    //get asset details
    $get_asset = new selects();
    $rows = $get_asset->fetch_details_cond('assets', 'asset_id',$id);
    foreach($rows as $row){
        $asset_qty = $row->quantity;
        $asset_cost = $row->cost * $quantity;
        $asset_ledger = $row->ledger;
        $accum_dep = $row->accum_dep * $quantity;
        $book_value = $row->book_value * $quantity;
    }
    $data = array(
        'asset' => $id,
        'reason' => $reason,
        'accum_dep' => $accum_dep,
        'amount' => $amount,
        'quantity' => $quantity,
        'disposed_date' => $date,
        'disposed_by' => $user,
        'trx_number' => $trx_num
    );
    //get asset ledger
    $get_asset_ledger = new selects();
    $ass_legs = $get_asset_ledger->fetch_details_cond('ledgers', 'acn', $asset_ledger);
    foreach($ass_legs as $ass){
        $asset_type = $ass->account_group;
        $asset_group = $ass->sub_group;
        $asset_class = $ass->class;
    }
    //get loss on asset disposal ledger
    $get_disposal = new selects();
    $diss = $get_disposal->fetch_details_cond('ledgers', 'class', 18);
    foreach($diss as $dis){
        $disposal_ledger = $dis->acn;
        $disposal_type = $dis->account_group;
        $disposal_group = $dis->sub_group;
        $disposal_class = $dis->class;
    }
    //get gain on asset disposal ledger
    $get_gain = new selects();
    $gains = $get_gain->fetch_details_cond('ledgers', 'class', 19);
    foreach($diss as $dis){
        $gain_ledger = $dis->acn;
        $gain_type = $dis->account_group;
        $gain_group = $dis->sub_group;
        $gain_class = $dis->class;
    }
    if($reason == "sold"){
        //get profit
        $profit = $amount - $book_value;
    }
    //get other expense details
    if($reason == "sold"){
        if($profit < 0){
            $proceeds = "loss";
            // $profit = -($profit);
            $expense_data = array(
                'income_head' => $id,
                'amount' => -($profit),
                'activity' => $proceeds,
                'details' => 'Disposal of Asset',
                'trx_number' => $trx_num,
                'post_date' => $date,
                'posted_by' => $user
            );
            $add_expense = new add_data('other_income', $expense_data);
            $add_expense->create_data();
        }else{
            $proceeds = "gain";
            $expense_data = array(
                'income_head' => $id,
                'amount' => $profit,
                'activity' => $proceeds,
                'details' => 'Disposal of Asset',
                'trx_number' => $trx_num,
                'post_date' => $date,
                'posted_by' => $user
            );
            $add_expense = new add_data('other_income', $expense_data);
            $add_expense->create_data();
        }
    }else{
        $loss = $book_value;
        $expense_data = array(
            'income_head' => $id,
            'amount' => $loss,
            'activity' => 'loss',
            'details' => 'Disposal of Asset',
            'trx_number' => $trx_num,
            'post_date' => $date,
            'posted_by' => $user
        );
        $add_expense = new add_data('other_income', $expense_data);
        $add_expense->create_data();
    }
    
    

    //get depreciation ledger
    $get_asset_dep = new selects();
    $depres = $get_asset_dep->fetch_details_cond('depreciation', 'asset', $id);
    if(gettype($depres) == 'array'){
        foreach($depres as $depre){
            $depre_id = $depre->contra_ledger;
        }
        $get_dep_ledger = new selects();
        $dep_ledgs = $get_dep_ledger->fetch_details_cond('ledgers', 'ledger_id', $depre_id);
        foreach($dep_ledgs as $dep_ledg){
            $depre_ledger = $dep_ledg->acn;
            $depre_type = $dep_ledg->account_group;
            $depre_group = $dep_ledg->sub_group;
            $depre_class = $dep_ledg->class;
        }
    }

   
    //check if quantity to remove is greater than vailable quantity
    if($quantity > $asset_qty){
        echo "<script>
            alert('Requested quantity is greater than available quantity');
            return
        </script>";
    }else{
        $new_quantity = $asset_qty - $quantity;
        //dispose asset
        $update_expense = new Update_table();
        $update_expense->update('assets', 'quantity', 'asset_id', $new_quantity, $id);
        //check if new quantity is = 0 to change d the asset satus
        if($new_quantity == 0)
            $update_expense->update('assets', 'asset_status', 'asset_id', -1, $id);
        }
        if($update_expense){
            $add_asset = new add_data('disposed_assets', $data);
            $add_asset->create_data();
            //get contra ledger
            if($reason == "sold"){
                $get_contra = new selects();
                $contrass = $get_contra->fetch_details_cond('ledgers', 'ledger_id', $contra);
                foreach($contrass as $contras){
                    $contra_ledger = $contras->acn;
                    $contra_type = $contras->account_group;
                    $contra_group = $contras->sub_group;
                    $contra_class = $contras->class;
                }
                //insert debit
                $debit_data = array(
                    'account' => $contra_ledger,
                    'account_type' => $contra_type,
                    'sub_group' => $contra_group,
                    'class' => $contra_class,
                    'details' => 'Sales of Asset',
                    'debit' => $amount,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $date
                );
                //add debit
                $add_debit = new add_data('transactions', $debit_data);
                $add_debit->create_data();
                //insert into transactions
                $credit_data = array(
                    'account' => $asset_ledger,
                    'account_type' => $asset_type,
                    'sub_group' => $asset_group,
                    'class' => $asset_class,
                    'credit' => $asset_cost,
                    'details' => 'Sales of Asset',
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $date

                );
                 //add credit
                 $add_credit = new add_data('transactions', $credit_data);
                 $add_credit->create_data();
                //check if there is depreciation
                if(gettype($depres) == 'array'){
                    $debit_data2 = array(
                        'account' => $depre_ledger,
                        'account_type' => $depre_type,
                        'sub_group' => $depre_group,
                        'class' => $depre_class,
                        'details' => 'Accum Depre on Asset Disposal',
                        'debit' => $accum_dep,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $date

                    );
                    $add_debit2 = new add_data('transactions', $debit_data2);
                    $add_debit2->create_data(); 
                }
                if($profit > 0){
                    $credit_profit = array(
                        'account' => $gain_ledger,
                        'account_type' => $gain_type,
                        'sub_group' => $gain_group,
                        'class' => $gain_class,
                        'credit' => $profit,
                        'details' => 'Gain on Sales of Asset',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $date

                    );
                     //add credit
                     $add_credit = new add_data('transactions', $credit_profit);
                     $add_credit->create_data();
                }
                if($profit < 0){
                    $debit_profit = array(
                        'account' => $disposal_ledger,
                        'account_type' => $disposal_type,
                        'sub_group' => $disposal_group,
                        'class' => $disposal_class,
                        'debit' => -($profit),
                        'details' => 'Loss on Sales of Asset',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $date
                    );
                     //add credit
                     $add_credit = new add_data('transactions', $debit_profit);
                     $add_credit->create_data();
                }
                  //cash flow data
                $flow_data = array(
                    'account' => $contra_ledger,
                    'details' => 'asset sales',
                    'trx_number' => $trx_num,
                    'amount' => $amount,
                    'trans_type' => 'inflow',
                    'activity' => 'investing',
                    'post_date' => $date,
                    'posted_by' => $user
                );
                $add_flow = new add_data('cash_flows', $flow_data);
                $add_flow->create_data();
            }else{
                
                //insert into transactions
                $credit_data = array(
                    'account' => $asset_ledger,
                    'account_type' => $asset_type,
                    'sub_group' => $asset_group,
                    'class' => $asset_class,
                    'credit' => $asset_cost,
                    'details' => 'Asset Disposed as Scrap',
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $date

                );
                 //add credit
                 $add_credit = new add_data('transactions', $credit_data);
                 $add_credit->create_data();
                 //insert debit
                $debit_data1 = array(
                    'account' => $disposal_ledger,
                    'account_type' => $disposal_type,
                    'sub_group' => $disposal_group,
                    'class' => $disposal_class,
                    'details' => 'Loss on Asset disposal',
                    'debit' => $book_value,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $date

                );
                //add debit
                $add_debit1 = new add_data('transactions', $debit_data1);
                $add_debit1->create_data();
                //check if there is depreciation
                if(gettype($depres) == 'array'){
                    $debit_data2 = array(
                        'account' => $depre_ledger,
                        'account_type' => $depre_type,
                        'sub_group' => $depre_group,
                        'class' => $depre_class,
                        'details' => 'Accum Depre on Asset Disposal',
                        'debit' => $accum_dep,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $date

                    );
                    $add_debit2 = new add_data('transactions', $debit_data2);
                    $add_debit2->create_data(); 
                }
                
            }
            
           
            echo "<div class='success'><p>Asset Disposed successfully!</p></div>";
        }