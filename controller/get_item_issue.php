<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // $_SESSION['store_to'] = htmlspecialchars(stripslashes($_POST['store_to']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_items_quantity($store, $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addIssue('<?php echo $row->item_id?>', '<?php echo $invoice?>')"><?php echo $row->item_name." (Quantity => ".round($row->quantity).")"?></a>
    </div>
   
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>