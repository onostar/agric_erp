<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $date = date("Y-m-d H:i:s");
    
    $product = htmlspecialchars(stripslashes($_POST['product']));
    $product_qty = htmlspecialchars(stripslashes($_POST['product_qty']));
    $invoice = htmlspecialchars(stripslashes($_POST['product_num']));
    $qty = htmlspecialchars(stripslashes($_POST['raw_qty']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            $item_name = $row->item_name;
        }
    }else{
        $item_name = "N/A";
    }
    ?>
    <div class="add_user_form" style="width:50%!important; margin:0!important">
        <h3 style="background:var(--tertiaryColor); text-align:left;">Add <?php echo strtoupper($item_name)?> for production. (Qty => <?php echo $qty?>kg)</h3>
        <section class="addUserForm" style="text-align:left!important;">
            <div class="inputs" style="flex-wrap:wrap;gap:.8rem;justify-content:none">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>" required>
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>" required>
                    <input type="hidden" name="product_number" id="product_number" value="<?php echo $invoice?>" required>
                    <input type="hidden" name="product" id="product" value="<?php echo $product?>" required>
                    <input type="hidden" name="product_qty" id="product_qty" value="<?php echo $product_qty?>" required>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $item?>" required>
                <div class="data" style="width:20%; margin:5px;">
                    <label for="item_quantity">Quantity (kg)</label>
                    <input type="number" name="item_quantity" id="item_quantity">
                </div>
                <div class="data" style="width:auto!important; margin:5px;">
                    <button type="submit" id="stockin" name="stockin" title="stockin item" onclick="addRawMaterial()"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </section>  
    </div>
    
<?php
    
    }else{
        echo "Your session has expired! Kindly Login again to continue";
        // header("Location: ../index.php");
    } 
?>