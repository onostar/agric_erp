<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item_raw']));
    /* $prod = htmlspecialchars(stripslashes($_POST['product']));
    $qty = htmlspecialchars(stripslashes($_POST['product_qty']));
    $invoice = htmlspecialchars(stripslashes($_POST['product_num'])); */
    /* $_SESSION['product'] = $prod;
    $_SESSION['product_qty'] = $qty;
    $_SESSION['product_num'] = $invoice; */
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    // $rows = $get_item->fetch_details_like1Cond('items', 'item_name', $item, 'item_type', 'Raw material');
    $rows = $get_item->fetch_items_quantity($store, $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
           
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="displayRawMaterial('<?php echo $row->item_id?>', '<?php echo $row->quantity?>')"><?php echo $row->item_name." (Quantity => ".$row->quantity."kg)"?></a>
    </div>
    <!-- <option onclick="displayRawMaterial('<?php echo $row->item_id?>')">
        <?php echo $row->item_name." (Quantity => ".$quantity."kg)"?>
    </option> -->
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>