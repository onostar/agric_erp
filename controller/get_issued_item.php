<?php
    session_start();
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $toDate = htmlspecialchars(stripslashes($_POST['toDate']));
    $fromDate = htmlspecialchars(stripslashes($_POST['fromDate']));
    $_SESSION['toDate'] = $toDate;
    $_SESSION['fromDate'] = $fromDate;
    $get_item = new selects();
    $rows = $get_item->fetch_details_like1Cond('items', 'item_name', $item, 'item_type', 'Consumable');
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>

    <option onclick="checkIssuedHistory('<?php echo $row->item_id?>')">
        <?php echo $row->item_name?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>