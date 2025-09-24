<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $posted_by = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    include "../classes/inserts.php";

    if(isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
        $trans_type = "production";
        //get product
        $get_product = new selects();
        $rows = $get_product->fetch_details_cond('ice_cream', 'product_number', $invoice);
        foreach($rows as $row){
            $product = $row->raw_material;
            $quantity = $row->raw_quantity;
            $store = $row->store;
        }
        //get item details from item table
        $get_details = new selects();
        $details = $get_details->fetch_details_cond('items', 'item_id', $product);
        foreach($details as $detail){
            // $cost_price = $detail->cost_price;
            $reorder_level = $detail->reorder_level;
            $product_name = $detail->item_name;
        }
        // get item previous quantity in inventory;
        $get_prev_qty = new selects();
        $prev_qtys = $get_prev_qty->fetch_details_2cond('inventory', 'item', 'store', $product, $store);
        if(gettype($prev_qtys) == 'array'){
            foreach($prev_qtys as $prev_qty){
                $inv_qty = $prev_qty->quantity;
            }
        }
        if(gettype($prev_qtys) == "string"){
            $inv_qty = 0;
        }
        //insert into audit trail
        $audit_data = array(
            'item' => $product,
            'transaction' => $trans_type,
            'previous_qty' => $inv_qty,
            'quantity' => $quantity,
            'posted_by' => $posted_by,
            'store' => $store,
            'post_date' => $date
        );
        $inser_trail = new add_data('audit_trail', $audit_data);
        $inser_trail->create_data();
        //check if item is in store inventory
        if(gettype($prev_qtys) == 'array'){
            //update current quantity in inventory
            $new_qty = intval($inv_qty) - intval($quantity);
            $update_inventory = new Update_table();
            $update_inventory->update2Cond('inventory', 'quantity', 'store', 'item', $new_qty, $store, $product);
            //update expiration
/*             $update_exp = new Update_table();
            $update_exp->update2cond('inventory', 'expiration_date', 'store', 'item', $expiration, $store, $item); */
        }
        //add to inventory if not found
        /* if(gettype($prev_qtys) == "string"){
            //data to insert
            $inventory_data = array(
                'item' => $product,
                'cost_price' => $cost_price,
                'expiration_date' => $expiration,
                'item_type' => 'Raw material',
                'quantity' => $quantity,
                'reorder_level' => $reorder_level,
                'store' => $store
            );
            $insert_item = new add_data('inventory', $inventory_data);
            $insert_item->create_data();
        } */
        //update all raw product items with this invoice
        $update = new Update_table();
        $update->update('ice_cream', 'product_status', 'product_number', 1, $invoice);

        if($update){
            echo "<div class='success'><p>Items added to inventory successfully! <i class='fas fa-thumbs-up'></i></p></div>";        
?>
    

<?php
        }
    }

}
    