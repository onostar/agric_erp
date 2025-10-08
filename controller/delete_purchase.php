<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    $store = $_SESSION['store_id'];
    $posted = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $purchase = $_GET['purchase_id'];
        $item = $_GET['item_id'];
        $trans_type = "purchase delete";
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/delete.php";
        include "../classes/inserts.php";

        //get item details
        $get_qty = new selects();
        $rows = $get_qty->fetch_details_cond('purchases', 'purchase_id', $purchase);
        foreach($rows as $row){
            $qty = $row->quantity;
            $invoice = $row->invoice;
            $supplier = $row->vendor;
        }
        // get item previous quantity in inventory;
        $prev_qtys = $get_qty->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
        if(is_array($prev_qtys)){
            foreach($prev_qtys as $inv){
                $inv_qty = $inv->quantity;
            }
        
        }else{
            $inv_qty = 0;
        }

        //data to insert into audit trail
        $audit_data = array(
            'item' => $item,
            'transaction' => $trans_type,
            'previous_qty' => $inv_qty,
            'quantity' => $qty,
            'posted_by' => $posted,
            'store' => $store,
            'post_date' => $date
        );
        
        $inser_trail = new add_data('audit_trail', $audit_data);
        $inser_trail->create_data();

        //update quantity on items table
        $update_qty = new Update_table();
        $update_qty->subtract_quantity($qty, $item, $store);
        if($update_qty){
            //delete purcahse
            $delete = new deletes();
            $delete->delete_item('purchases', 'purchase_id', $purchase);
            if($delete){
    include "../controller/stockin_details.php";
            
            }            
        }
    // }
?>