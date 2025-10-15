<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $accepted = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        
        $trans_type = "purchase";
        $id = htmlspecialchars(stripslashes($_POST['item_id']));
        $item = htmlspecialchars(stripslashes($_POST['item']));
        $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
        $vendor = htmlspecialchars(stripslashes($_POST['vendor']));
        $balance = htmlspecialchars(stripslashes($_POST['balance_qty']));
        $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
        $cost_price = htmlspecialchars(stripslashes($_POST['cost']));
        $ordered = htmlspecialchars(stripslashes($_POST['ordered']));
        $supplied = htmlspecialchars(stripslashes($_POST['supplied']));
        
    
    //instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $date = date("Y-m-d H:i:s");
    //get items
    $get_item = new selects();
    //get item details 
    $itemss = $get_item->fetch_details_cond('items', 'item_id', $item);
    foreach($itemss as $items){
        $name = $items->item_name;
        $reorder_level = $items->reorder_level;
    }
    //check if item exists in inventory
    $invs = $get_item->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
    if(is_array($invs)){
        // get item previous quantity in inventory;
        foreach($invs as $inv){
            $inv_qty = $inv->quantity;
        }
        $new_qty = $inv_qty + $quantity;
        $update_inventory = new Update_table();
        $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $cost_price, 'item', $item, 'store', $store);
    }else{
        $inv_qty = 0;
        //data to insert
        $inventory_data = array(
            'item' => $item,
            'cost_price' => $cost_price,
            // 'expiration_date' => $expiration,
            'quantity' => $quantity,
            'reorder_level' => $reorder_level,
            'store' => $store,
            'post_date' => $date
        );
        $insert_item = new add_data('inventory', $inventory_data);
        $insert_item->create_data();
    }
    //insert into audit trail
    $audit_data = array(
        'item' => $item,
        'transaction' => $trans_type,
        'previous_qty' => $inv_qty,
        'quantity' => $quantity,
        'posted_by' => $accepted,
        'store' => $store,
        'post_date' => $date
    );
    $inser_trail = new add_data('audit_trail', $audit_data);
    $inser_trail->create_data();
    //update quantity in purchase order
    $total_supplied = $supplied + $quantity;
    $update = new Update_table();
    $update->update('purchase_order', 'supplied', 'purchase_id', $total_supplied, $id);
    if($update){
        //insert into accept po table
        $accept_data = array(
            'purchase_id' => $id,
            'item' => $item,
            'balance_qty' => $balance,
            'supplied' => $quantity,
            'accepted_by' => $accepted,
            'store' => $store,
            'accept_date' => $date
        );
        $accept_po = new add_data('accept_po', $accept_data);
        $accept_po->create_data();
        //insert into purchase table
        $purchase_data = array(
            'item' => $item,
            'invoice' => $invoice,
            'cost_price' => $cost_price,
            'vendor' => $vendor,
            // 'sales_price' => $sales_price,
            // 'expiration_date' => $expiration,
            'quantity' => $quantity,
            'posted_by' => $accepted,
            'store' => $store,
            'post_date' => $date
        );
        $stock_in = new add_data('purchases', $purchase_data);
        $stock_in->create_data();
        //check if ordered quantity is met and update delivery status
        if($total_supplied >= $ordered){
            $update->update('purchase_order', 'delivery_status', 'purchase_id', 1, $id);
        }
        echo "<div class='success' style='padding:4px!important'><p style='color:#fff!important'><span>$quantity $name</span> accepted into inventory</p>";
    }
?>
    
<?php
        }else{
            echo "Your session has expired! Please Login to continue";
        }
  
?>