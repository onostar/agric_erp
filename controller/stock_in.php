<?php
date_default_timezone_set("Africa/Lagos");

    session_start();
    $trans_type ="purchase";
    $type = htmlspecialchars(stripslashes($_POST['item_type'])); 
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item_id']));
    // $supplier = htmlspecialchars(stripslashes($_POST['supplier']));
    $supplier = htmlspecialchars(stripslashes($_POST['vendor']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice_number']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $cost_price = htmlspecialchars(stripslashes($_POST['cost_price']));
    /* $sales_price = htmlspecialchars(stripslashes($_POST['sales_price']));
    $pack_price = htmlspecialchars(stripslashes($_POST['pack_price']));
    $wholesale = htmlspecialchars(stripslashes($_POST['wholesale_price']));
    $wholesale_pack = htmlspecialchars(stripslashes($_POST['wholesale_pack']));
    $pack_size = htmlspecialchars(stripslashes($_POST['pack_size'])); */
    // $expiration = htmlspecialchars(stripslashes($_POST['expiration_date']));
    // $guest_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $date = date("Y-m-d H:i:s");
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get reordr level
    $get_details = new selects();
    $row = $get_details->fetch_details_group('items', 'reorder_level', 'item_id', $item);
    $reorder_level = $row->reorder_level;
    // get item previous quantity in inventory;
    $prev_qtys = $get_details->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
    if(is_array($prev_qtys)){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
        }
        //update current quantity in inventory
        $new_qty = $inv_qty + $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $cost_price, 'item', $item, 'store', $store);
    }else{
        $inv_qty = 0;
        //data to insert into inventory
        $inventory_data = array(
            'item' => $item,
            'cost_price' => $cost_price,
            // 'expiration_date' => $expiration,
            'quantity' => $quantity,
            'reorder_level' => $reorder_level,
            'store' => $store,
            'item_type' => $type
        );
        $insert_item = new add_data('inventory', $inventory_data);
        $insert_item->create_data();
    }

    //data to insert into audit trail
    $audit_data = array(
        'item' => $item,
        'transaction' => $trans_type,
        'previous_qty' => $inv_qty,
        'quantity' => $quantity,
        'posted_by' => $posted,
        'store' => $store,
        'post_date' => $date
    );
    //insert into audit trail
    $inser_trail = new add_data('audit_trail', $audit_data);
    $inser_trail->create_data();
    //stockin item
    //data to stockin into purchases
    $purchase_data = array(
        'item' => $item,
        'invoice' => $invoice,
        'cost_price' => $cost_price,
        'vendor' => $supplier,
        // 'sales_price' => $sales_price,
        // 'expiration_date' => $expiration,
        'quantity' => $quantity,
        'posted_by' => $posted,
        'store' => $store,
        'post_date' => $date
    );
    $stock_in = new add_data('purchases', $purchase_data);
    $stock_in->create_data();
    
    if($stock_in){
        //check if item price needs to be updated
        $check = new selects();
        $count = $check->fetch_count_2cond('prices', 'item', $item, 'store', $store);
        if($count > 0){
            //update price
            $update_price = new Update_table();
            $update_price->update_tripple2Cond('prices', 'cost', $cost_price, 'updated_at', $date, 'updated_by', $posted, 'item', $item, 'store', $store);
            
        }else{
            //insert price
            $price_data = array(
                'item' => $item,
                'store' => $store,
                'cost' => $cost_price,
                'added_at' => $date,
                'added_by' => $posted
            );
            $insert_price = new add_data('prices', $price_data);
            $insert_price->create_data();
        }
        //update all prices and pack size
        /* $update_item = new Update_table();
        $update_item->update('items', 'cost_price', 'item_id',  $cost_price, $item); */
        // if($update_price || $insert_price){
        //update expiration
        /* $update_exp = new Update_table();
        $update_exp->update('items', 'expiration_date', 'item_id', $expiration, $item); */

        

    //display stockins for this invoice number
    include "../controller/stockin_details.php";
        // }
    }
?>