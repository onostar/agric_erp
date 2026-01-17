<?php
    date_default_timezone_set("Africa/Lagos");
    $trans_type ="issue";    
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $store_from = htmlspecialchars(stripslashes($_POST['store_from']));
    // $store_to = htmlspecialchars(stripslashes($_POST['store_to']));
    $item = htmlspecialchars(stripslashes($_POST['item_id']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $date = date("Y-m-d H:i:s");
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get item details 
    $get_item_det = new selects();
    $itemss = $get_item_det->fetch_details_cond('items', 'item_id', $item);
    foreach($itemss as $items){
        // $price = $items->sales_price;
        $name = $items->item_name;
    }
    //check if item already exist in the request invoice
    $check = $get_item_det->fetch_count_2cond('issue_items', 'invoice', $invoice, 'item', $item);
    if($check > 0){
         echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$name</span> already exist in request order! Cannot proceed</p></div>";
        echo "<script>alert('$name already exist in request order! Cannot proceed')</script>";
        include "item_request_details.php";
    }else{
    // get item previous quantity in inventory;
    $get_prev_qty = new selects();
    $prev_qtys = $get_prev_qty->fetch_details_2cond('inventory', 'item', 'store', $item, $store_from);
    if(gettype($prev_qtys) === 'array'){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
            $cost_price = $prev_qty->cost_price;

            // $expiration = $prev_qty->expiration_date;
        }
    }
    //get staff department
    $stfs = $get_item_det->fetch_details_cond('staffs', 'user_id', $posted);
    if(is_array($stfs)){
        foreach($stfs as $stf){
            $department = $stf->department;
        }
    }else{
        $department = 3;
    }
    //check item quantity
    if($quantity > $inv_qty){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$name</span> do not have enough quantity! Cannot proceed</p></div>";
        echo "<script>alert('$name do not have enough quantity! Cannot proceed')</script>";
        include "item_request_details.php";
    }else{
    
    //data to issue
    $transfer_data = array(
        'item' => $item,
        'invoice' => $invoice,
        // 'sales_price' => $price,
        'cost_price' => $cost_price,
        'quantity' => $quantity,
        'posted_by' => $posted,
        'department' => $department,
        // 'expiration' => $expiration,
        'from_store' => $store_from,
        'post_date' => $date,
        // 'to_store' => $store_to
    );
    $transfer = new add_data('issue_items', $transfer_data);
    $transfer->create_data();
    
    if($transfer){
        

        include "item_request_details.php";


        }
    
    }
    }
?>