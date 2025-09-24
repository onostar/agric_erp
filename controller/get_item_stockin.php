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
    // $rows = $get_item->fetch_details_likeNegCond('items', 'item_name', $item, 'item_type', 'Product');
    $rows = $get_item->fetch_details_likeNegCond('items', 'item_name', $item, 'item_type', 'Product');
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
    <div class="results">
        <a href="javascript:void(0)" onclick="displayStockinForm('<?php echo $row->item_id?>')"><?php echo $row->item_name." (Quantity => ".$quantity."kg)"?></a>
    </div>
    
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>