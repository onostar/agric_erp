<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // $_SESSION['store_to'] = htmlspecialchars(stripslashes($_POST['store_to']));
    $_SESSION['issue_invoice'] = htmlspecialchars(stripslashes($_POST['invoice']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_like('items', 'item_name', $item);
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
    ?>
    <option onclick="addIssue('<?php echo $row->item_id?>')">
        <?php echo $row->item_name." (Quantity => ".$quantity.")"?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>