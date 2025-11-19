<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $trans_type ="pruning";

    // $crop = htmlspecialchars(stripslashes($_POST['crop']));
    $leaves =  "LEAVES";
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $task = htmlspecialchars(stripslashes($_POST['task_id']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));

    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get item details
    $get_details = new selects;
    //get cycle details
    $cycs = $get_details->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
    foreach($cycs as $cyc){
        $field = $cyc->field;
    }
    //get field details
    $fids = $get_details->fetch_details_cond('fields', 'field_id', $field);
    foreach($fids as $fid){
        $field_name = $fid->field_name;
    }
    //generate transaction number
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    $trx_num = "TR".$ran_num.$todays_date;
    $rows = $get_details->fetch_details_cond('items', 'item_name', $leaves);
    foreach($rows as $row){
        $item_id = $row->item_id;
        $item_type = $row->item_type;
        $reorder_level = $row->reorder_level;
        $crop_name = $leaves;
        $cost = $row->cost_price;
    }
    $details = "Leaves removed from $field_name";
   
    
    $prev_qtys = $get_details->fetch_details_2cond('inventory', 'item', 'store', $item_id, $farm);
    if(is_array($prev_qtys)){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
        }
         //update current quantity in inventory
        $new_qty = $inv_qty + $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $cost, 'item', $item_id, 'store', $farm);
    }else{
        $inv_qty = 0;
        //data to insert into inventory
        $inventory_data = array(
            'item' => $item_id,
            'cost_price' => $cost,
            // 'expiration_date' => $expiration,
            'quantity' => $quantity,
            'reorder_level' => $reorder_level,
            'store' => $farm,
            'item_type' => $item_type
        );
        $insert_item = new add_data('inventory', $inventory_data);
        $insert_item->create_data();
    }
     //data to insert into audit trail
    $audit_data = array(
        'item' => $item_id,
        'transaction' => $trans_type,
        'previous_qty' => $inv_qty,
        'quantity' => $quantity,
        'posted_by' => $user,
        'store' => $farm,
        'post_date' => $date
    );
    //insert into audit trail
    $inser_trail = new add_data('audit_trail', $audit_data);
    $inser_trail->create_data();
    
    //stockin item
    //data to stockin into leaves_removal table
    $leaves_data = array(
        'cycle' => $cycle,
        'task_id' => $task,
        'crop' => $item_id,
        'farm' => $farm,
        'field' => $field,
        'quantity' => $quantity,
        'trx_number' => $trx_num,
        'removed_by' => $user,
        'date_removed' => $date
    );
    $stock_in = new add_data('pruning', $leaves_data);
    $stock_in->create_data();
    if($stock_in){
        //add into accounting data
        
        //total cost for inventory
        $total_cost = $cost * $quantity;
        //get farm input ledger
        $inps = $get_details->fetch_details_cond('ledgers', 'ledger', 'FARM INPUTS');
        foreach($inps as $inp){
            $input_ledger = $inp->acn;
            $input_type = $inp->account_group;
            $input_sub_group = $inp->sub_group;
            $input_class = $inp->class;

        }
        
        //get inventory legder id
        $invs = $get_details->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
        foreach($invs as $inv){
            $inventory_ledger = $inv->acn;
            $inv_type = $inv->account_group;
            $inv_sub_group = $inv->sub_group;
            $inv_class = $inv->class;
        }
        //credit farm inputs
        $credit_inputs = array(
            'account' => $input_ledger,
            'account_type' => $input_type,
            'sub_group' => $input_sub_group,
            'class' => $input_class,
            'credit' => $total_cost,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'details' => $details,
            'trans_date' => $date,
            'store' => $farm
        );
        //debit inventory
        $debit_data = array(
            'account' => $inventory_ledger,
            'account_type' => $inv_type,
            'sub_group' => $inv_sub_group,
            'class' => $inv_class,
            'debit' => $total_cost,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'details' => $details,
            'trans_date' => $date,
            'store' => $farm
        );
        //add debit
        $add_debit = new add_data('transactions', $debit_data);
        $add_debit->create_data();      
        //add credit for inputs
        $add_credit = new add_data('transactions', $credit_inputs);
        $add_credit->create_data();
        
        echo "<div class='success'><p>$quantity kg of $leaves(S) Removed from $field_name successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to remove leaves from $field_name <i class='fas fa-thumbs-down'></i></p>";
    }