<?php
    session_start();
    $item = htmlspecialchars(stripslashes($_POST['item']));
    $store = $_SESSION['store_id'];
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $vendor = htmlspecialchars(stripslashes($_POST['vendor']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
    $_SESSION['vendor'] = $vendor;
    $_SESSION['purchase_invoice'] = $invoice;
    $get_item = new selects();
    
    $rows = $get_item->fetch_items_quantity($store, $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="results">
        <?php if($row->item_type == "Consumable"){?>
        <a href="javascript:void(0)" onclick="displayStockinForm('<?php echo $row->item_id?>')"><?php echo $row->item_name." (Quantity => ".round($row->quantity).")"?></a>
        <?php }else{?>
        <a href="javascript:void(0)" onclick="displayStockinForm('<?php echo $row->item_id?>')"><?php echo $row->item_name." (Quantity => ".$row->quantity."kg)"?></a>
        <?php }?>
    </div>
    
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>