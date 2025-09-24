<?php

    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('assets', 'asset_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //get location name
            $get_loc = new selects();
            $locs = $get_loc->fetch_details_group('asset_locations', 'location', 'location_id', $row->location);
            $location = $locs->location;
            
    ?>
    <div class="add_user_form priceForm" style="width:100%">
        <h3 style="background:var(--tertiaryColor)">Alocate or Reassign <?php echo strtoupper($row->asset)?></h3>
        <section class="addUserForm" style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:.2rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="asset_id" id="asset_id" value="<?php echo $row->asset_id?>" required>
                <div class="data" style="width:32%">
                    <label for="cost_price">Description</label>
                    <input type="text" value="<?php echo $row->asset?>" readonly style="background:#e6e4e4">
                </div>
                <div class="data" style="width:32%">
                    <label for="cost_price">Cost price (NGN)</label>
                    <input type="text" name="cost_price" id="cost_price" value="<?php echo number_format($row->cost, 2)?>" readonly style="background:#e6e4e4">
                </div>
                <div class="data" style="width:32%">
                    <label for="sales_price">Asset Number
                    <input type="text" value="<?php echo $row->asset_no?>" readonly style="background:#e6e4e4">
                </div>
                <div class="data" style="width:32%">
                    <label for="sales_price">Quantity
                    <input type="text" name="quantity" id="quantity" value="<?php echo $row->quantity?>">
                </div>
                <div class="data" style="width:32%">
                    <label for="location">Reason for disposal</label>
                    <select name="reason" id="reason" onchange="checkDisposeReason(this.value)">
                        <option value="">Select Reason</option>
                        <option value="scrapped">SCRAPPED</option>
                        <option value="sold">SOLD</option>
                    </select>
                </div>
                <div class="data" id="contra_form" style="width:32%">
                <label for="contra"><span class="ledger">Cr. </span>Contra Ledger</label>
                    <select name="contra" id="contra">
                        <option value="" selected>Select Contra ledger</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" id="amount_form" style="width:32%">
                    <label for="amount"><span class="ledger">Amount Sold</label>
                    <input type="number" name="amount" id="amount">
                </div>
                <div class="data" style="width:auto">
                    <button type="submit"onclick="disposeAsset()">Dispose <i class="fas fa-save"></i></button>
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