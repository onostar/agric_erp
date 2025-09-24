<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        if(isset($_GET['item'])){
            $item = $_GET['item'];
            $date = "05-09-2025";
    
    $invoice = $_SESSION['product_num'];
    $material = $_SESSION['material'];
    $material_qty = $_SESSION['material_qty'];

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get quantity from inventory
    $get_qty = new selects();
    $qtyss = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
    if(gettype($qtyss) == 'array'){
        foreach($qtyss as $qtys){
            $qty = $qtys->quantity;
        }
    }
    if(gettype($qtyss) == 'string'){
        $qty = 0;
    }

    /* if($qty == 0){
        echo "<script>
        alert('Item has zero quantity! Cannot proceed');
        </script>";
    }else{ */
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
    ?>
    <div class="add_user_form" style="width:50%!important; margin:0!important">
        <h3 style="background:brown; text-align:left;">Add <?php echo strtoupper($row->item_name)?> for production. (Qty => <?php echo $qty?>)</h3>
        <section class="addUserForm" style="text-align:left!important;">
            <div class="inputs" style="flex-wrap:wrap;gap:.8rem;justify-content:none">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>" required>
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>" required>
                    <input type="hidden" name="product_number" id="product_number" value="<?php echo $invoice?>" required>
                    <input type="hidden" name="material" id="material" value="<?php echo $material?>" required>
                    <input type="hidden" name="material_qty" id="material_qty" value="<?php echo $material_qty?>" required>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                <div class="data" style="width:20%; margin:5px;">
                    <label for="item_quantity">Quantity</label>
                    <input type="number" name="item_quantity" id="item_quantity">
                </div>
                <div class="data" style="width:auto!important; margin:5px;">
                    <button type="submit" id="stockin" name="stockin" title="stockin item" onclick="addIceProduct()"><i class="fas fa-plus"></i></button>
                </div>
            </div>
        </section>  
    </div>
    
<?php
    endforeach;
     }
    }
    // }
    }else{
        header("Location: ../index.php");
    } 
?>