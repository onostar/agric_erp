<?php
    session_start();
    $store = $_SESSION['store_id'];    
    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('purchase_order', 'purchase_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //get item name
            $name = $get_item->fetch_details_group('items', 'item_name', 'item_id', $row->item);
        
    ?>
    <div class="add_user_form priceForm" style="width:60%; margin:0!important;">
        <h3 style="background:var(--tertiaryColor); text-align:left">Receive quantity purchased for <?php echo strtoupper($name->item_name)?></h3>
        <section style="text-align:left;">
            <div class="inputs" style="justify-content:left">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $id?>" required>
                    <input type="hidden" name="cost" id="cost" value="<?php echo $row->cost_price?>" required>
                    <input type="hidden" name="item" id="item" value="<?php echo $row->item?>" required>
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $row->invoice?>">
                    <input type="hidden" name="vendor" id="vendor" value="<?php echo $row->vendor?>">
                    <input type="hidden" name="ordered" id="ordered" value="<?php echo $row->quantity?>">
                    <input type="hidden" name="supplied" id="supplied" value="<?php echo $row->supplied?>">
                    <input type="hidden" name="balance_qty" id="balance_qty" value="<?php echo $row->quantity - $row->supplied?>">
                <div class="data" style="width:40%">
                    <label for="sales_price">Quantity received</label>
                    <input type="text" name="quantity" id="quantity" value="<?php echo $row->quantity - $row->supplied?>">
                </div>
                <div class="data" style="width:auto; display:flex; gap:.3rem">
                    <button type="submit" id="adjust_qty" name="adjust_qty" onclick="acceptOrder()"> Save <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:15px; color:#fff; box-shadow: 1px 1px 1px #222; border:1px solid #fff;' onclick="closeForm()">Close <i class='fas fa-close'></i></a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>