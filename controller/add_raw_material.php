<?php
date_default_timezone_set("Africa/Lagos");

    $trans_type ="production";
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $item = htmlspecialchars(stripslashes($_POST['item_id']));
    $product = htmlspecialchars(stripslashes($_POST['product']));
    $invoice = htmlspecialchars(stripslashes($_POST['product_number']));
    $item_quantity = htmlspecialchars(stripslashes($_POST['item_quantity']));
    $product_qty = htmlspecialchars(stripslashes($_POST['product_qty']));
    // $guest_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $date = date("Y-m-d H:i:s");
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get raw material cost
    $get_cost = new selects();
    $costs = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $item);
    $cost = $costs->cost_price;
    //check if raw material already exists in production with same prodct number;
    $check_material = new selects();
    $check = $check_material->fetch_details_2cond('production', 'product_number', 'raw_material', $invoice, $item);
    if(gettype($check) == 'array'){
        echo "<script>
            alert('Raw material already used for this production');
        </script>";
    include "raw_material_details.php";
    }else{
    //check if quantity is greater than quantity in inventory
    $check_qty = new selects();
    $qtyss = $check_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
    foreach($qtyss as $qtys){
        $prev_qty = $qtys->quantity;
    }
    if($item_quantity > $prev_qty){
        echo "<script>
            alert('Quantity available is less than required! Can not proceed');
        </script>";
    include "raw_material_details.php";

    }else{
        //data to insert into production table
        $production_data = array(
            'raw_material' => $item,
            'product' => $product,
            'product_qty' => $product_qty,
            'product_number' => $invoice,
            'raw_quantity' => $item_quantity,
            'unit_cost' => $cost,
            'store' => $store,
            'posted_by' => $posted,
            'post_date' => $date
        );
        $insert_item = new add_data('production', $production_data);
        $insert_item->create_data();
    
    if($insert_item){
    
        //insert into audit trail
        $audit_data = array(
            'item' => $item,
            'transaction' => $trans_type,
            'previous_qty' => $prev_qty,
            'quantity' => $item_quantity,
            'posted_by' => $posted,
            'store' => $store,
            'post_date' => $date
        );
        $inser_trail = new add_data('audit_trail', $audit_data);
        $inser_trail->create_data();
        //update raw material stock balance
        $new_qty = $prev_qty - $item_quantity;
        $update_qty = new Update_table();
        $update_qty->update2cond('inventory', 'quantity', 'item', 'store', $new_qty, $item, $store);
        //display stockins for this invoice number 
        include "raw_material_details.php";
    }
        }
    }
?>