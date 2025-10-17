<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_items_quantity($store, $item);
    // $rows = $get_item->fetch_details_like1Cond('items', 'item_name', $item, 'item_type', 'Product');
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
          
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addProduce('<?php echo $row->item_id?>', '<?php echo $row->item_name?>')"><?php echo $row->item_name." (Quantity => ".$row->quantity."kg)"?></a>
    </div>
    
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>