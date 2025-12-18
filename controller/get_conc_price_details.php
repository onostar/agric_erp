<?php
    session_start();
    $store = $_SESSION['store_id'];
    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_2cond('prices', 'item', 'store', $id, $store);
    if(gettype($rows) == 'array'){
        foreach($rows as $row){
            $cost = $row->cost;
            $sales = $row->sales_price;
            $other_price = $row->other_price;
        }
    }else{
        $cost = 0;
        $sales = 0;
        $other_price = 0;
    }
       //get item name
    $item_rows = $get_item->fetch_details_cond('items', 'item_id', $id);
    foreach($item_rows as $ro){
        $item_name = $ro->item_name;
    }  
    ?>
    <div class="add_user_form priceForm">
        <h3 style="background:var(--tertiaryColor)">Update price for <?php echo strtoupper($item_name)?></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:.2rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $id?>" required>
                <div class="data" style="width:23%">
                    <label for="cost_price">Cost price (NGN)</label>
                    <input type="text" name="cost_price" id="cost_price" value="<?php echo $cost?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="sales_price">Sales Price (NGN)</label>
                    <input type="text" name="sales_price" id="sales_price" value="<?php echo $sales?>">
                </div>
                <input type="hidden" name="other_price" id="other_price" value="<?php echo $other_price?>">
                <div class="data" style="width:auto">
                    <button type="submit" id="change_price" name="change_price" onclick="changeItemPrice('concentrate_price.php')">Save <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return <i class='fas fa-angle-double-left'></i></a>
                </div>
                
            </div>
        </section>   
    </div>
    
<?php
   
    }    
?>