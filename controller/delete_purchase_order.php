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
        // get item name;
        $items = $get_qty->fetch_details_group('items', 'item_name', 'item_id', $item);
        $item_name = $items->item_name;
        
        //delete purcahse
        $delete = new deletes();
        $delete->delete_item('purchases', 'purchase_id', $purchase);
        if($delete){
            echo "<div class='notify'><p>$item_name removed from purchaase order successfully</p></div>";

    include "../controller/purchase_order_details.php";
            
                       
        }
    // }
?>