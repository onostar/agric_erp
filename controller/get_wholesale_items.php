<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get customer type to determine price
    /* $get_customer = new selects();
    $rows = $get_customer->fetch_details_group('customers', 'customer_type', 'customer_id', $customer);
    $type = $rows->customer_type; */
    $get_item = new selects();
    $rows = $get_item->fetch_items_quantity($store, $item);
     if(is_array($rows)){
        foreach($rows as $row):
        
        // if($type == "Dealer"){
    ?>
    
    <div class="results">
            <a href="javascript:void(0)" onclick="addWholeSales('<?php echo $row->item_id?>')"><?php echo $row->item_name." (Price => ₦".$row->sales_price.", Quantity => ".$row->quantity."kg)"?></a>
        </div>
<?php
    // }
    endforeach;
     }else{
        echo "No result found";
     }
?>