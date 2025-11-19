<div id="assets" class="displays">
    <style>
        label{
            font-size:.8rem!important;
        }
    </style>
<?php

    if (isset($_GET['asset'])){
        $id = $_GET['asset'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('assets', 'asset_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            
            
    ?>
    <div class="add_user_form priceForm" style="margin:10px 50px;">
        <h3 style="background:var(--tertiaryColor)">UPDATE "<?php echo strtoupper($row->asset)?>" DETAILS</h3>
        <form style="width:100%;padding:20px;">
            <div class="inputs" style="gap:.7rem; align-items:flex-start!important;justify-content:left; flex-wrap:wrap;">
                <input type="hidden" id="asset_id" name="asset_id" value="<?php echo $id?>">
                <div class="data" style="width:23%">
                    <label for="asset">Asset Name</label>
                    <input type="text" name="asset" id="asset" value="<?php echo $row->asset?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="asset">Quantity</label>
                    <input type="number" name="quantity" id="quantity" value="<?php echo $row->quantity?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="supplier">Manufacturer/Supplier</label>
                    <input type="text" name="supplier" id="supplier" value="<?php echo $row->supplier?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="purchase_date">Purchase Date</label>
                    <input type="date" name="purchase_date" id="purchase_date"value="<?php echo date("Y-m-d", strtotime($row->purchase_date))?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="asset_cost">Asset Cost (NGN)</label>
                    <input type="text" name="cost" id="cost" value="<?php echo $row->cost?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="salvage_value">Salvage Value (NGN)</label>
                    <input type="number" name="salvage_value" id="salvage_value" value="<?php echo $row->salvage_value?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="useful_life">Useful Life (Years)</label>
                    <input type="number" name="useful_life" id="useful_life" value="<?php echo $row->useful_life?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="deployment">Deployment Date</label>
                    <input type="date" name="deployment" id="deployment" value="<?php echo date("Y-m-d", strtotime($row->deployment_date))?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="location">Asset Location</label>
                    <select name="location" id="location">
                        <?php
                            //get location details
                            $get_loc = new selects();
                            $locs = $get_loc->fetch_details_group('stores', 'store', 'store_id', $row->location);
                        ?>
                        <option value="<?php echo $row->location?>"selected required><?php echo $locs->store?></option>
                        <?php
                            $results = $get_item->fetch_details_negCond1('stores', 'store_id', $row->location);
                            foreach($results as $result){
                        ?>
                        <option value="<?php echo $result->store_id?>"><?php echo $result->store?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="ledger">Asset Account</label>
                    <select name="ledger" id="ledger">
                        <?php
                            //get ledger details
                            $ledg = $get_item->fetch_details_group('ledgers', 'ledger', 'acn', $row->ledger);
                        ?>
                        <option value="<?php echo $row->ledger?>"selected required><?php echo $ledg->ledger?></option>
                        <?php
                            $get_dep = new selects();
                            $acns = $get_dep->fetch_details_cond('ledgers', 'class', 5);
                            foreach($acns as $ac){
                        ?>
                        <option value="<?php echo $ac->acn?>"><?php echo $ac->ledger?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:45%">
                    <label for="class">Specification/Details</label>
                    <textarea name="specification" id="specification"><?php echo $row->specification?></textarea>
                </div>
                <div class="data" style="width:auto; margin-top:10px;">
                    <button type="button" id="add_cat" name="add_cat" onclick="updateAsset()">Save record <i class="fas fa-layer-group"></i></button>
                    <a href="javascript:void(0)" title="return" onclick="showPage('asset_register.php')" style="border-radius:15px; border:1px solid #fff; box-shadow: 1px 1px 1px #222; background:brown;color:#fff; padding:8px"><i class="fas fa-angle-double-left"></i> Return</a>
                </div>
            </div>
            
        </form>    
    </div>
    
<?php
    endforeach;
     }
    }    
?>
</div>