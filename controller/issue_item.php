<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $id = htmlspecialchars(stripslashes($_POST['id']));
    $item = htmlspecialchars(stripslashes($_POST['item']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
    $trans_type = "issue";
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
        $cost_price = $items->cost_price;
        // $price = $items->sales_price;
        $name = $items->item_name;
    }
    //get item request details
    $results = $get_item_det->fetch_details_cond('issue_items', 'issue_id', $id);
    foreach($results as $result){
        $quantity = $result->quantity;
        $store = $result->from_store;
    }
    // get item previous quantity in inventory;
    $get_prev_qty = new selects();
    $prev_qtys = $get_prev_qty->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
    if(gettype($prev_qtys) === 'array'){
        foreach($prev_qtys as $prev_qty){
            $inv_qty = $prev_qty->quantity;
            // $expiration = $prev_qty->expiration_date;
        }
    }
    //check item quantity
    if($quantity > $inv_qty){
        echo "<div class='notify' style='padding:4px!important'><p style='color:#fff!important'><span>$name</span> do not have enough quantity! Cannot proceed</p>";
        echo "<script>alert('$name do not have enough quantity! Cannot proceed')</script>";
    }else{
    //insert into audit trail
    //data to insert in audit trail
    $audit_data = array(
        'item' => $item,
        'transaction' => $trans_type,
        'previous_qty' => $inv_qty,
        'quantity' => $quantity,
        'posted_by' => $user,
        'store' => $store,
        'post_date' => $date
    );
    $inser_trail = new add_data('audit_trail', $audit_data);
    $inser_trail->create_data();
    //check if item is in store inventory
    $check_item = new selects();
    if(gettype($prev_qtys) === 'array'){
        //update current quantity in inventory
        $new_qty = $inv_qty - $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update2Cond('inventory', 'quantity', 'store', 'item', $new_qty, $store, $item);
    }
    
    //transfer item
    //data to issue
    $transfer_data = array(
        'issued_by' => $user,
        'date_issued' => $date,
        'issue_status' => 2,
    );
    $update = new Update_table();
    $update->updateAny('issue_items', $transfer_data, 'issue_id', $id);
    
    if($update){
 
        echo "<div class='success'><p>$quantity $name issued out successfully</p></div>";
        }
    
    }
?>