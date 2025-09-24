<?php
    session_start();

    $item = htmlspecialchars(stripslashes($_POST['item']));
    $_SESSION['store_to'] = htmlspecialchars(stripslashes($_POST['store_to']));
    $_SESSION['invoice'] = htmlspecialchars(stripslashes($_POST['invoice']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_like2Cond('items', 'item_name', 'barcode', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <option onclick="addTransfer('<?php echo $row->item_id?>')">
        <?php echo $row->item_name?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>