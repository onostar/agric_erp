<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="add_category" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:90%;">
        <h3 style="background:var(--tertiaryColor)">Add New Asset</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm" style="width:100%;padding:20px;">
            <div class="inputs" style="gap:.7rem; align-items:flex-start!important">
                <div class="data" style="width:23%">
                    <label for="asset">Asset Name</label>
                    <input type="text" name="asset" id="asset">
                </div>
                <div class="data" style="width:23%">
                    <label for="asset">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="1">
                </div>
                <div class="data" style="width:23%">
                    <label for="supplier">Manufacturer/Supplier Name</label>
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
                    <label for="useful_life">Useful Life</label>
                    <input type="number" name="useful_life" id="useful_life">
                </div>
                <div class="data" style="width:25%">
                    <label for="deployment">Deployment Date</label>
                    <input type="date" name="deployment" id="deployment">
                </div>
                <div class="data" style="width:30%">
                    <label for="location">Asset Location</label>
                    <select name="location" id="location">
                        <option value=""selected required>Select location</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details('asset_locations');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->location_id?>"><?php echo $row->location?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:30%">
                    <label for="ledger">Asset Account</label>
                    <select name="ledger" id="ledger">
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
                <div class="data" style="width:35%">
                    <label for="class">Specification</label>
                    <textarea name="specification" id="specification"></textarea>
                </div>
                
            </div>
            <div class="inputs">
                <button type="submit" id="add_cat" name="add_cat" onclick="addAsset()">Save record <i class="fas fa-layer-group"></i></button>
            </div>
        </section>    
    </div>
</div>
