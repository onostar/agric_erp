<?php
date_default_timezone_set("Africa/Lagos");
    session_start();
    $trans_type ="purchase order";
    $type = htmlspecialchars(stripslashes($_POST['item_type'])); 
    $posted = htmlspecialchars(stripslashes($_POST['posted_by']));
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item_id']));
    // $supplier = htmlspecialchars(stripslashes($_POST['supplier']));
    $supplier = htmlspecialchars(stripslashes($_POST['vendor']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice_number']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $cost_price = htmlspecialchars(stripslashes($_POST['cost_price']));
    $date = date("Y-m-d H:i:s");
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    include "../classes/select.php";
    
    $get_details = new selects();
    //get item name
    $results = $get_details->fetch_details_group('items', 'item_name', 'item_id', $item);
    $item_name = $results->item_name;
    //check if item already exist in purchase order
    $checks = $get_details->fetch_count_3cond('purchase_order', 'item', $item, 'invoice', $invoice, 'vendor', $supplier);
    if($checks > 0){
        echo "<script>alert('$item_name already exist in purchase order');</script>";
        echo "<div class='notify'><p>$item_name already exist in purchase order</p></div>";
        
    }else{
         //data to stockin into purchase order
        $data = array(
            'item' => $item,
            'invoice' => $invoice,
            'cost_price' => $cost_price,
            'vendor' => $supplier,
            'quantity' => $quantity,
            'posted_by' => $posted,
            'store' => $store,
            'post_date' => $date
        );
        $stock_in = new add_data('purchase_order', $data);
        $stock_in->create_data();
        
        if($stock_in){
            echo "<div class='notify'><p>$item_name added to purchaase order successfully</p></div>";
   

    //display purchase order for this invoice number
        }
    }
    include "../controller/purchase_order_details.php";

?>