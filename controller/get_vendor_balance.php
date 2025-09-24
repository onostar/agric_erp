<?php

    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('vendors', 'vendor_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
        
    ?>
    <div class="add_user_form priceForm" style="width:60%; margin:0">
        <h3 style="background:var(--tertiaryColor)">Post balance to <?php echo strtoupper($row->vendor)?></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:.2rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="vendor" id="vendor" value="<?php echo $row->vendor_id?>" required>
                
                
                <div class="data" style="width:23%">
                    <label for="sales_price">Amount (NGN)</label>
                    <input type="text" name="amount" id="amount">
                </div>
                
                <div class="data" style="width:auto">
                    <button type="submit" id="change_price" name="change_price" onclick="postBalance()">Post <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return <i class='fas fa-angle-double-left'></i></a>
                </div>
                
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>