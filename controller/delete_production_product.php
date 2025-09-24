<?php
    
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $production = $_GET['production_id'];
        $item = $_GET['item_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";
        include "../classes/update.php";
// echo $item;
        //get item details
        $get_item = new selects();
        $row = $get_item->fetch_details_group('items', 'item_name', 'item_id', $item);
        $name = $row->item_name;
        //get invoice and quantity
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('ice_cream', 'product_id', $production);
        foreach($rows as $row){
            $invoice = $row->product_number;
            $quantity = $row->product_qty;
            $material = $row->raw_material;
            $store = $row->store;
        }
        //get product from the invoice
        $get_product = new selects();
        $prd = $get_product->fetch_details_group('ice_cream', 'product', 'product_number', $invoice);
        $product = $prd->product;
        //get previous quantity in inventory
        $get_inv = new selects();
        $invs = $get_inv->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
        foreach($invs as $inv){
            $prev_qty = $inv->quantity;
        }
        //add previous quantity to curent quantity transfered
        $new_qty = intval($prev_qty) - intval($quantity);
        //delete from production
        $delete = new deletes();
        $delete->delete_item('ice_cream', 'product_id', $production);
        if($delete){
            //update inventory
            $update_inventory = new Update_table();
            $update_inventory->update2cond('inventory', 'quantity', 'store', 'item', $new_qty, $store, $item);

?>
<!-- display items with same invoice number -->
<div class="notify"><p><span><?php echo $name?></span> Removed from Production</p></div>
 <!-- display transfers for this invoice number -->
 <?php include "ice_product_details.php"?>
  
<?php
            }            
        
    // }
?>