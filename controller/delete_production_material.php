<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $trans_type = "production delete";

    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $production = $_GET['production_id'];
        $item = $_GET['item_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";
        include "../classes/update.php";
        include "../classes/inserts.php";
// echo $item;
        //get item details
        $get_item = new selects();
        $row = $get_item->fetch_details_group('items', 'item_name', 'item_id', $item);
        $name = $row->item_name;
        //get invoice and quantity
        $rows = $get_item->fetch_details_cond('production', 'product_id', $production);
        foreach($rows as $row){
            $invoice = $row->product_number;
            $quantity = $row->raw_quantity;
            $store = $row->store;
            $product = $row->product;
            $trx_num = $row->trx_number;
        }
       
        //get previous quantity in inventory
        $invs = $get_item->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
        foreach($invs as $inv){
            $prev_qty = $inv->quantity;
        }
        //add previous quantity to curent quantity transfered
        $new_qty = $prev_qty + $quantity;
        //delete from production
        $delete = new deletes();
        $delete->delete_item('production', 'product_id', $production);
        if($delete){
            //update inventory
            $update_inventory = new Update_table();
            $update_inventory->update2cond('inventory', 'quantity', 'store', 'item', $new_qty, $store, $item);
            //data to insert into audit trail
            $audit_data = array(
                'item' => $item,
                'transaction' => $trans_type,
                'previous_qty' => $prev_qty,
                'quantity' => $quantity,
                'posted_by' => $user,
                'store' => $store,
                'post_date' => $date
            );
            
            $inser_trail = new add_data('audit_trail', $audit_data);
            $inser_trail->create_data();
            //delete from transactions
            $delete->delete_item('transactions', 'trx_number', $trx_num);
?>
<!-- display items with same invoice number -->
<div class="notify"><p><span><?php echo $name?></span> Removed from Production materials</p></div>
 <!-- display transfers for this invoice number -->
 <?php include "raw_material_details.php"?>
  
<?php
            }            
        
    // }
?>