<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $trans_type ="harvest";

    // $crop = htmlspecialchars(stripslashes($_POST['crop']));
    $crop =  1; //pineapple
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
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
        $yield = $cyc->expected_yield;
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
    $rows = $get_details->fetch_details_cond('items', 'item_id', $crop);
    foreach($rows as $row){
        $item_type = $row->item_type;
        $reorder_level = $row->reorder_level;
        $crop_name = $row->item_name;
        // $cost = $row->cost_price;
    }
    $details = "Harvested $quantity of Pineapples from $field_name field";
    //get ratio of expected yield to quantity harvested
    $ratio = $quantity / $yield;
    //get cycle tasks cost and items used for task total cost
    $labs = $get_details->fetch_sum_single('tasks', 'labour_cost', 'cycle', $cycle);
    if(is_array($labs)){
        foreach($labs as $lab){
            $labour_cost = $lab->total;
        }
    }else{
        $labour_cost = 0;
    }
    //get materials cost
    $mats = $get_details->fetch_sum_single('task_items', 'total_cost', 'cycle', $cycle);
    if(is_array($mats)){
        foreach($mats as $mat){
            $materials_cost = $mat->total;
        }
    }else{
        $materials_cost = 0;
    }
    //check if an harvest has been done before for this cycle and get previous quantities
    $prev_harvs = $get_details->fetch_sum_single('harvests', 'quantity', 'cycle', $cycle);
    if(is_array($prev_harvs)){
        foreach($prev_harvs as $harv){
            $previous_harvest = $harv->total;
        }
    }else{
        $previous_harvest = 0;
    }
    //total harvest quantity
    $total_quantity = $quantity + $previous_harvest;
    //total cost
    $cost = $labour_cost + $materials_cost;
    //get unit cost price for product based on total harvest
    $unit_cost = $cost / $total_quantity;
    
    $prev_qtys = $get_details->fetch_details_2cond('inventory', 'item', 'store', $crop, $farm);
    if(is_array($prev_qtys)){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
        }
         //update current quantity in inventory
        $new_qty = $inv_qty + $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $unit_cost, 'item', $crop, 'store', $farm);
    }else{
        $inv_qty = 0;
        //data to insert into inventory
        $inventory_data = array(
            'item' => $crop,
            'cost_price' => $unit_cost,
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
        'item' => $crop,
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
    //update crop cost in price table
    $update_item = new Update_table();
    // $update_item->update('items', 'cost_price', 'item_id', $unit_cost, $crop);
    $update_item->update2cond('prices', 'cost', 'item', 'store',  $unit_cost, $crop, $farm);
    //stockin item
    //data to stockin into harvest
    $harvest_data = array(
        'cycle' => $cycle,
        'crop' => $crop,
        'farm' => $farm,
        'field' => $field,
        'quantity' => $quantity,
        'unit_cost' => $unit_cost,
        'posted_by' => $user,
        'post_date' => $date
    );
    $stock_in = new add_data('harvests', $harvest_data);
    $stock_in->create_data();
    if($stock_in){
        //add into accounting data
        //cost for production labour
        $total_labour_cost = $labour_cost * $ratio;
        //cost for farm inputs
        $input_cost = $materials_cost * $ratio;
        //total cost for inventory
        $total_cost = $total_labour_cost + $input_cost;
        //get farm input ledger
        $inps = $get_details->fetch_details_cond('ledgers', 'ledger', 'FARM INPUTS');
        foreach($inps as $inp){
            $input_ledger = $inp->acn;
            $input_type = $inp->account_group;
            $input_sub_group = $inp->sub_group;
            $input_class = $inp->class;

        }
        //get production labour cost ledger
        $prls = $get_details->fetch_details_cond('ledgers', 'ledger', 'PRODUCTION LABOUR');
        foreach($prls as $prl){
            $pro_ledger = $prl->acn;
            $pro_type = $prl->account_group;
            $pro_sub_group = $prl->sub_group;
            $pro_class = $prl->class;
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
            'credit' => $input_cost,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'details' => $details,
            'trans_date' => $date,
            'store' => $farm
        );
        //credit labour cost
        $credit_labour = array(
            'account' => $pro_ledger,
            'account_type' => $pro_type,
            'sub_group' => $pro_sub_group,
            'class' => $pro_class,
            'credit' => $total_labour_cost,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'details' => $details,
            'trans_date' => $date,
            'store' => $farm
        );
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
        //add credit for labour
        $add_credit = new add_data('transactions', $credit_labour);
        $add_credit->create_data();
        //check if total quantity harvested has reached expected yield to close cycle
        /* if($total_quantity >= $yield){
            //update cycle to closed
            $update_cycle = new Update_table();
            $update_cycle->update('crop_cycles', 'cycle_status', 'cycle_id', 1, $cycle);
            //update field status to available
            $update_field = new Update_table();
            $update_field->update('fields', 'field_status', 'field_id', 0, $field);
        } */
        echo "<div class='success'><p>$quantity Pineapples Harvested successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to harvest crop <i class='fas fa-thumbs-down'></i></p>";
    }