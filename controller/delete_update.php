<?php
        session_start();
        $store = $_SESSION['store_id'];
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $sales = $_GET['sales_id'];
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
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('sales', 'sales_id', $sales);
        foreach($rows as $row){
            $invoice = $row->invoice;
            $quantity = $row->quantity;

        }
        // check item current quantity in inventory
        $check_qty = new selects();
        $qtys = $check_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
        foreach($qtys as $qty){
            $current_qty = $qty->quantity;

        }
        //add back to inventory inventory
        $new_qty = $current_qty + $quantity;
        $update_inv = new Update_table();
        $update_inv->update2cond('inventory', 'quantity', 'item', 'store', $new_qty, $item, $store);
        //delete sales
        $delete = new deletes();
        $delete->delete_item('sales', 'sales_id', $sales);
        if($delete){
?>
<!-- display items with same invoice number -->
<div class="notify"><p><span><?php echo $name?></span> Removed from sales order</p></div>

</div>    
<?php
    include "update_details.php";
            }            
        
    // }
?>