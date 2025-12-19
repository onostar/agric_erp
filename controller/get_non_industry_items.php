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
        //get item price
        $prs = $get_item->fetch_details_2cond('prices', 'item', 'store', $row->item_id, $store);
        if(is_array($prs)){
            foreach($prs as $pr){
                $price = $pr->other_price;
            }
        }else{
            $price = 0;
        }
        // if($type == "Dealer"){
    ?>
    <?php if($row->item_name == "CONCENTRATE"){?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addWholeSales('<?php echo $row->item_id?>', 'add_other_sales.php')"><?php echo $row->item_name." (Price => ₦".$price.", Quantity => ".$row->quantity."Ltr)"?></a>
    </div>
<?php
    }else{
?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addWholeSales('<?php echo $row->item_id?>', 'add_other_sales.php')"><?php echo $row->item_name." (Price => ₦".$price.", Quantity => ".$row->quantity."kg)"?></a>
    </div>
<?php
     }
    endforeach;
     }else{
        echo "No result found";
     }
?>