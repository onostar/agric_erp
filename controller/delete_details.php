<?php
        session_start();
    // if(isset($_GET['id'])){
    //     $id = $_GET['id'];
        $invoice = $_GET['invoice'];
        $item = $_GET['item_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";
// echo $item;
        //get item details
        /* $get_item = new selects();
        $row = $get_item->fetch_details_group('invoices', 'drug', 'prescription_id', $item);
        $drug = $row->drug; */
        
        //delete prescription
        $delete = new deletes();
        $delete->delete_item('invoices', 'invoice_id', $item);
        if($delete){
?>
<!-- display items with same invoice number -->
<div class="notify"><p>Item Removed from Invoice</p></div>

</div>    
<?php
    include "invoice_details.php";
            }            
        
    // }
?>