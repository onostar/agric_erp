<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="assets" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:90%;">
        <h3 style="background:var(--tertiaryColor)">Add New Asset</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form style="width:100%;padding:20px;">
            <div class="inputs" style="gap:.7rem; align-items:flex-start!important;justify-content:left; flex-wrap:wrap;">
                <div class="data" style="width:24%">
                    <label for="ledger">Asset Account</label>
                    <select name="ledger" id="ledger" onchange="checkAssetType(this.value)">
                        <option value=""selected required>Select Asset Account</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details_cond('ledgers', 'class', 5);
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->acn?>"><?php echo $row->ledger?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="asset">Asset Name</label>
                    <input type="text" name="asset" id="asset">
                </div>
                <div class="data" style="width:23%" id="qty_div">
                    <label for="asset">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="1">
                </div>
                <div class="data" style="width:23%" id="land_div">
                    <label for="Size">Size (plot)</label>
                    <input type="number" name="size" id="size" value="1">
                </div>
                <div class="data" style="width:23%">
                    <label for="supplier">Manufacturer/Supplier</label>
                    <input type="text" name="supplier" id="supplier">
                </div>
                <div class="data" style="width:23%">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="date" name="purchase_date" id="purchase_date">
                </div>
                <div class="data" style="width:23%">
                    <label for="asset_cost">Asset Cost (NGN)</label>
                    <input type="text" name="cost" id="cost">
                </div>
                <div class="data" style="width:23%">
                    <label for="salvage_value">Salvage Value (NGN)</label>
                    <input type="number" name="salvage_value" id="salvage_value">
                </div>
                <div class="data" style="width:23%">
                    <label for="useful_life">Useful Life (Years)</label>
                    <input type="number" name="useful_life" id="useful_life">
                </div>
                <div class="data" style="width:23%">
                    <label for="deployment">Deployment Date</label>
                    <input type="date" name="deployment" id="deployment">
                </div>
                <div class="data" style="width:24%">
                    <label for="location">Asset Location</label>
                    <select name="location" id="location">
                        <option value=""selected required>Select location</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details_order('stores', 'store');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->store_id?>"><?php echo $row->store?></option>
                        <?php } ?>
                    </select>
                </div>
                
                <div class="data" style="width:45%">
                    <label for="class">Specification/Details</label>
                    <textarea name="specification" id="specification"></textarea>
                </div>
                
            </div>
            <div class="inputs">
                <button type="button" id="add_cat" name="add_cat" onclick="addAsset()">Save record <i class="fas fa-layer-group"></i></button>
            </div>
        </form>    
    </div>
</div>
