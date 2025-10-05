<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $trans_type ="harvest";

    $crop = htmlspecialchars(stripslashes($_POST['crop']));
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));

    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get item details
    $get_details = new selects;
    $rows = $get_details->fetch_details_cond('items', 'item_id', $crop);
    foreach($rows as $row){
        $item_type = $row->item_type;
        $reorder_level = $row->reorder_level;
        $crop_name = $row->item_name;
        $cost = $row->cost_price;
    }
    //gert fields
    $fds = $get_details->fetch_details_group('crop_cycles', 'field', 'cycle_id', $cycle);
    $field = $fds->field;
    $prev_qtys = $get_details->fetch_details_2cond('inventory', 'item', 'store', $crop, $farm);
    if(is_array($prev_qtys)){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
        }
    }else{
        $inv_qty = 0;
    }
    //check if item is in store inventory
    if(gettype($prev_qtys) === 'array'){
        //update current quantity in inventory
        $new_qty = $inv_qty + $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $cost, 'item', $crop, 'store', $farm);
    }
    //add to inventory if not found
    if(gettype($prev_qtys) === 'string'){
        //data to insert into inventory
        $inventory_data = array(
            'item' => $crop,
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
    //stockin item
    //data to stockin into harvest
    $harvest_data = array(
        'cycle' => $cycle,
        'crop' => $crop,
        'farm' => $farm,
        'field' => $field,
        'quantity' => $quantity,
        'posted_by' => $user,
        'post_date' => $date
    );
    $stock_in = new add_data('harvests', $harvest_data);
    $stock_in->create_data();
    if($stock_in){
        echo "<div class='success'><p>$quantity $crop_name Harvested successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to harvest crop <i class='fas fa-thumbs-down'></i></p>";
    }