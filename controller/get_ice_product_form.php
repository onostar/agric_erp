<?php
    session_start();
    $store = $_SESSION['store_id'];
    $product = htmlspecialchars(stripslashes($_POST['product']));
    $item = htmlspecialchars(stripslashes($_POST['material']));
    $qty = htmlspecialchars(stripslashes($_POST['material_qty']));
    $invoice = htmlspecialchars(stripslashes($_POST['product_num']));
    $_SESSION['material'] = $item;
    $_SESSION['material_qty'] = $qty;
    $_SESSION['product_num'] = $invoice;
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_like1Cond('items', 'item_name', $product, 'item_type', 'Product');
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
    <option onclick="displayIceProduct('<?php echo $row->item_id?>')">
        <?php echo $row->item_name." (Quantity => ".$quantity.")"?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>