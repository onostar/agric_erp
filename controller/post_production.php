<?php
date_default_timezone_set("Africa/Lagos");
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
         $details = "New Produce from production";
        //get current date
        $todays_date = date("dmyhis");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        //generate transaction number
        $trx_num = "TR".$ran_num.$todays_date;
        //get product
        $get_product = new selects();
        $rows = $get_product->fetch_details_cond('production', 'product_number', $invoice);
        foreach($rows as $row){
            $product = $row->product;
            $quantity = $row->product_qty;
            $store = $row->store;
        }
        //get cost of production and divide among total product made
        $costs = $get_product->fetch_sum_2colCond('production', 'raw_quantity', 'unit_cost', 'product_number', $invoice);
        foreach($costs as $cost){
            $total_cost = $cost->total;
        }
        $unit_cost = $total_cost/$quantity;
        //get item details from item table
        $results = $get_product->fetch_details_cond('items', 'item_id', $product);
        foreach($results as $result){
            // $cost_price = $detail->cost_price;
            $reorder_level = $result->reorder_level;
            $product_name = $result->item_name;
        }
        // get item previous quantity in inventory;
        $prev_qtys = $get_product->fetch_details_2cond('inventory', 'item', 'store', $product, $store);
        if(is_array($prev_qtys)){
            foreach($prev_qtys as $prev_qty){
                $inv_qty = $prev_qty->quantity;
            }
            $new_qty = $inv_qty + $quantity;
            $update_inventory = new Update_table();
            $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $unit_cost, 'store', $store, 'item', $product);
        }else{
            $inv_qty = 0;
            $inventory_data = array(
                'item' => $product,
                'cost_price' => $unit_cost,
                // 'expiration_date' => $expiration,
                'item_type' => 'Product',
                'quantity' => $quantity,
                'reorder_level' => $reorder_level,
                'store' => $store
            );
            $insert_item = new add_data('inventory', $inventory_data);
            $insert_item->create_data();
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
        //update cost price in items table
        $update_cost = new Update_table();
        $update_cost->update('items', 'cost_price', 'item_id', $unit_cost, $product);
        //update all raw materialsitems with this invoice
        $update = new Update_table();
        $update->update('production', 'product_status', 'product_number', 1, $invoice);
        //enter transactions
        //get production ledger
        $inps = $get_details->fetch_details_cond('ledgers', 'ledger', 'PRODUCTION INPUTS');
        foreach($inps as $inp){
            $contra_ledger = $inp->acn;
            $contra_type = $inp->account_group;
            $contra_sub_group = $inp->sub_group;
            $contra_class = $inp->class;

        }
        //get inventory legder id
        $invs = $get_details->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
        foreach($invs as $inv){
            $inventory_ledger = $inv->acn;
            $inv_type = $inv->account_group;
            $inv_sub_group = $inv->sub_group;
            $inv_class = $inv->class;
        }
        //credit production inputs
        $credit_inputs = array(
            'account' => $contra_ledger,
            'account_type' => $contra_type,
            'sub_group' => $contra_sub_group,
            'class' => $contra_class,
            'credit' => $total_cost,
            'post_date' => $date,
            'posted_by' => $posted_by,
            'trx_number' => $trx_num,
            'details' => $details,
            'trans_date' => $date,
            'store' => $store
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
        if($update){
            echo "<div class='success'><p>$quantity $product_name added to inventory successfully! <i class='fas fa-thumbs-up'></i></p></div>";        
?>

<?php
        }
    }

}
