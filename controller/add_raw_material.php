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
    $date = date("Y-m-d H:i:s");
    $details = "Item used for production";
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    //generate transaction number
    $trx_num = "TR".$ran_num.$todays_date;
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get raw material cost
    $get_details = new selects();
    $costs = $get_details->fetch_details_group('items', 'cost_price', 'item_id', $item);
    $cost = $costs->cost_price;
    $total_cost = $cost * $item_quantity;
    //check if raw material already exists in production with same prodct number;
    $check = $get_details->fetch_details_2cond('production', 'product_number', 'raw_material', $invoice, $item);
    if(gettype($check) == 'array'){
        echo "<script>
            alert('Raw material already inthis production order');
        </script>";
    include "raw_material_details.php";
    }else{
        //check if quantity is greater than quantity in inventory
        $qtyss = $get_details->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
        if(is_array($qtyss)){
            foreach($qtyss as $qtys){
                $prev_qty = $qtys->quantity;
            }
        }else{
            $prev_qty = 0;
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
                'trx_number' => $trx_num,
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
            //insert into transactions
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
            $credit_data = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'credit' => $total_cost,
                'post_date' => $date,
                'posted_by' => $posted,
                'trx_number' => $trx_num,
                'details' => $details,
                'trans_date' => $date,
                'store' => $store
            );
            $debit_data = array(
                'account' => $contra_ledger,
                'account_type' => $contra_type,
                'sub_group' => $contra_sub_group,
                'class' => $contra_class,
                'debit' => $total_cost,
                'post_date' => $date,
                'posted_by' => $posted,
                'trx_number' => $trx_num,
                'details' => $details,
                'trans_date' => $date,
                'store' => $store

            );
            //add debit
            $add_debit = new add_data('transactions', $debit_data);
            $add_debit->create_data();      
            //add credit
            $add_credit = new add_data('transactions', $credit_data);
            $add_credit->create_data();
            //display stockins for this invoice number 
            include "raw_material_details.php";
        }
        }
    }
?>