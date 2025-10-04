<?php
    session_start();
    $store = $_SESSION['store_id'];
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_likeCond('items', 'item_name', $item/*  'item_type', 'Farm Input' */);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //get item quantity from inventory
            $qtys = $get_item->fetch_details_2cond('inventory', 'store', 'item', $store, $row->item_id);
            if(gettype($qtys) == 'array'){
                foreach($qtys as $qty){
                    $quantity = $qty->quantity;
                }
            }
            if(gettype($qtys) == 'string'){
                $quantity = 0;
            }
        if($quantity > 0){
    ?>
    <div class="results">
        <a href="javascript:void(0)"  onclick="selectCycleItem('<?php echo $row->item_id?>', '<?php echo $row->item_name?>', '<?php echo $quantity?>')"><?php echo $row->item_name?> (Qty => <?php echo $quantity?>)</a>
    </div>
    <?php }else{?>
    <div class="results">
        <a href="javascript:void(0)"  onclick="alert('Item has no quantity! Cannot proceed')"><?php echo $row->item_name?> (Qty => <?php echo $quantity?>)</a>
    </div>

<?php
    }
    endforeach;
     }else{
        echo "No resullt found";
     }
?>