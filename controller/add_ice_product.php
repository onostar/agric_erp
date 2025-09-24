<?php

    $trans_type ="production";
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $item = htmlspecialchars(stripslashes($_POST['item_id']));
    $material = htmlspecialchars(stripslashes($_POST['material']));
    $invoice = htmlspecialchars(stripslashes($_POST['product_number']));
    $item_quantity = htmlspecialchars(stripslashes($_POST['item_quantity']));
    $material_qty = htmlspecialchars(stripslashes($_POST['material_qty']));
    // $guest_id = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
    $date = date("Y-m-d H:i:s");
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get raw material cost
    $get_cost = new selects();
    $costs = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $material);
    $cost = $costs->cost_price;
    //get product details
    $get_product = new selects();
    $prods = $get_product->fetch_details_cond('items', 'item_id', $item);
    foreach($prods as $prod){
        $reorder_level = $prod->reorder_level;
    }
    //check if product already exists in production with same prodct number;
    $check_material = new selects();
    $check = $check_material->fetch_details_2cond('ice_cream', 'product_number', 'product', $invoice, $item);
    if(gettype($check) == 'array'){
        echo "<script>
            alert('Product already in this production');
        </script>";
    include "ice_product_details.php";
    }else{
    //get previous quantity of product
    $check_qty = new selects();
    $qtyss = $check_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
    if(gettype($qtyss) == 'array'){
        foreach($qtyss as $qtys){
            $prev_qty = $qtys->quantity;
        }
        
    }
    if(gettype($qtyss) == 'string'){
        $prev_qty = 0;
    }
        //data to insert into production table
        $production_data = array(
            'product' => $item,
            'raw_material' => $material,
            'product_qty' => $item_quantity,
            'product_number' => $invoice,
            'raw_quantity' => $material_qty,
            'unit_cost' => $cost,
            'store' => $store,
            'posted_by' => $posted,
            'post_date' => $date
        );
        $insert_item = new add_data('ice_cream', $production_data);
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
        if(gettype($qtyss) == "array"){
            //update product stock balance
        $new_qty = intval($prev_qty) + intval($item_quantity);
        $update_qty = new Update_table();
        $update_qty->update2cond('inventory', 'quantity', 'item', 'store', $new_qty, $item, $store);
        }
        
        //add to inventory if not found
        if(gettype($qtyss) == "string"){
            //data to insert
            $inventory_data = array(
                'item' => $item,
                /* 'cost_price' => $cost_price,
                'expiration_date' => $expiration, */
                'item_type' => 'Product',
                'quantity' => $item_quantity,
                'reorder_level' => $reorder_level,
                'store' => $store,
                'post_date' => $date
            );
            $insert_item = new add_data('inventory', $inventory_data);
            $insert_item->create_data();
        }
        //display stockins for this invoice number 
        include "ice_product_details.php";
    }
        }
    // }
?>