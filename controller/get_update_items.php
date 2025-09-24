<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get customer type to determine price
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_group('customers', 'customer_type', 'customer_id', $customer);
    $type = $rows->customer_type;
    $get_item = new selects();
    $rows = $get_item->fetch_details_like1Cond('items', 'item_name', $item, 'item_type', 'Product');
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //get item quantity from inventory
            $get_qty = new selects();
            $qtys = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $row->item_id);
            if(gettype($qtys) == 'array'){
                foreach($qtys as $qty){
                    $quantity = $qty->quantity;
                }
            }
            if(gettype($qtys) == 'string'){
                $quantity = 0;
            }
        
        if($type == "Dealer"){
    ?>
    <option onclick="addUpdateSales('<?php echo $row->item_id?>')">
        <?php echo $row->item_name." (Price => ₦".$row->wholesale.", Quantity => ".$quantity.")"?>
    </option>
    <?php }else{ ?>
        <option onclick="addUpdateSales('<?php echo $row->item_id?>')">
        <?php echo $row->item_name." (Price => ₦".$row->sales_price.", Quantity => ".$quantity.")"?>
    </option>
<?php
    }
    endforeach;
     }else{
        echo "No result found";
     }
?>