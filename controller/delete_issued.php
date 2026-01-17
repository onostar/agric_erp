<?php
    
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $transfer = $_GET['issue_id'];
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
        $rows = $get_invoice->fetch_details_cond('issue_items', 'issue_id', $transfer);
        foreach($rows as $row){
            $invoice = $row->invoice;
            $quantity = $row->quantity;
            $store_from = $row->from_store;
        }
        //delete from transfer
        $delete = new deletes();
        $delete->delete_item('issue_items', 'issue_id', $transfer);
        if($delete){
            //update inventory
            

?>
<!-- display items with same invoice number -->
<div class="notify"><p><span><?php echo $name?></span> Removed from Item Requests</p></div>
 <!-- display transfers for this invoice number -->
 
<?php
        include "item_request_details.php";

            }            
        
    // }
?>