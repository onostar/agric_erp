<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        if(isset($_GET['cycle'])){
            $cycle = $_GET['cycle'];
            $get_cycle = new selects();
            $details = $get_cycle->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
            foreach($details as $detail){
                $cycle_id = $detail->cycle_id;
                $field = $detail->field;
                $area_used = $detail->area_used;
                $crop = $detail->crop;
                $variety = $detail->variety;
                $start_date = $detail->start_date;
                $expected_harvest = $detail->expected_harvest;
                $expected_yield = $detail->expected_yield;
                $other_notes = $detail->notes;
                $status = $detail->cycle_status;
            }
            //get field name
            $fids = $get_cycle->fetch_details_cond('fields', 'field_id', $field);
            foreach($fids as $fid){
                $field_name = $fid->field_name;
            }
            //get crop name
            /* $crps = $get_cycle->fetch_details_cond('items', 'item_id', $crop);
            foreach($crps as $crp){
                $crop_name = $crp->item_name;
            } */
            //check if there is any harvest for the cycle
            $harvs = $get_cycle->fetch_count_cond('harvests', 'cycle', $cycle);
            //check if there is any task for the cycle
            $tasks = $get_cycle->fetch_count_cond('tasks', 'cycle', $cycle);
?>

<div id="crop_cycle" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('crop_cycle.php')" title="Return fot farm fields">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:60%; margin:20px"></div>
    <div class="add_user_form" style="width:70%; margin:20px">
        <h3 style="background:var(--moreColor)">Update Cycle Details for <?php echo $field_name?> (CY0<?php echo $cycle_id?>)</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                <?php if($tasks > 0 || $harvs > 0){?>
                <div class="data" style="width:30%">
                    <label for="field">Field</label>
                    <select name="field" id="field" required readonly>
                        <option value="<?php echo $field?>" selected><?php echo $field_name?></option>
                    </select>
                </div>
                <?php }else{?>
                    <div class="data" style="width:30%">
                    <label for="field">Field</label>
                    <select name="field" id="field" required>
                        <option value="<?php echo $field?>" selected><?php echo $field_name?></option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details_2cond('fields', 'farm', 'field_status', $store, 0);
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->field_id?>"><?php echo $row->field_name?></option>
                        <?php } ?>
                    </select>
                </div>
                <?php }?>
                <div class="data" style="width:30%;">
                   <label for="area_used"> Field Area Used (Hec)</label>
                   <input type="number" name="area_used" id="area_used" value="<?php echo $area_used?>"required>
                </div>
                <?php /* if($tasks > 0 || $harvs > 0 ){*/?>
                <!-- <div class="data" style="width:30%">
                    <label for="crop">Crop</label>
                    <input type="text" name="item" id="item" value="<?php echo $crop_name?>"readonly>
                    <input type="hidden" name="crop" id="crop" value="<?php echo $crop?>" readonly>
                </div>
                <div class="data" style="width:30%;">
                   <label for="variety">Crop Variety</label>
                   <input type="text" name="variety" id="variety" required value="<?php echo $variety?>" readonly>
                </div> -->
                <?php /* }else{ */?>
                <!-- <div class="data" style="width:30%">
                    <label for="crop">Crop</label>
                    <input type="text" name="item" id="item" value="<?php echo $crop_name?>" oninput="getCrops(this.value)" required>
                    <div id="search_results" style="position:relative;">

                    </div>
                    <input type="hidden" name="crop" id="crop" value="<?php echo $crop?>">
                </div>
                <div class="data" style="width:30%;">
                   <label for="variety">Crop Variety</label>
                   <input type="text" name="variety" id="variety" value="<?php echo $variety?>" required>
                </div> -->
                <?php /* } */?>
                
                <div class="data" style="width:30%;">
                   <label for="start_date">Start Date</label>
                   <input type="date" name="start_date" id="start_date" required value="<?php echo date("Y-m-d", strtotime($start_date))?>">
                </div>
                <!-- <div class="data" style="width:30%;">
                   <label for="harvest">Expected Harvest Date</label>
                   <input type="date" name="harvest" id="harvest" value="<?php echo date("Y-m-d", strtotime($expected_harvest))?>" required>
                </div> -->
                <?php /* if($harvs > 0){ */?>
                <!-- <div class="data" style="width:30%;">
                   <label for="harvest">Expected Yield</label>
                   <input type="number" name="yield" id="yield" value="<?php echo $expected_yield?>" readonly>
                </div> -->
                <?php /* }else{ */?>
               <!--  <div class="data" style="width:30%;">
                   <label for="harvest">Expected Yield</label>
                   <input type="number" name="yield" id="yield" value="<?php echo $expected_yield?>" required>
                </div> -->
                <?php /* } */?>
                <div class="data" style="width:65%;">
                   <label for="harvest">Other Notes</label>
                   <input type="text" name="notes" id="notes" value="<?php echo $other_notes?>">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" id="add_item" name="add_item" onclick="editCropCycle()">Update record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php
    }else{
        echo "<p style='text-align:center; color:red; font-weight:bold'>No crop cycle selected for edit</p>";
    }
}else{
    echo "<p style='text-align:center; color:red; font-weight:bold'>Please login to continue</p>";
}