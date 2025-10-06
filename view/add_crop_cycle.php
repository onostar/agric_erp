<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

<div id="crop_cycle" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('crop_cycle.php')" title="Return fot farm fields">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:60%; margin:20px"></div>
    <div class="add_user_form" style="width:70%; margin:20px">
        <h3 style="background:var(--moreColor)">Create a New Crop Cycle</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <div class="data" style="width:30%">
                    <label for="field">Field</label>
                    <select name="field" id="field" required>
                        <option value=""selected disabled>Select Farm Field</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details('fields');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->field_id?>"><?php echo $row->field_name?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:30%;">
                   <label for="area_used"> Field Area Used (Hec)</label>
                   <input type="number" name="area_used" id="area_used" required>
                </div>
                <div class="data" style="width:30%">
                    <label for="crop">Crop</label>
                    <input type="text" name="item" id="item" oninput="getCrops(this.value)" placeholder="Enter crop name" required>
                    <div id="search_results" style="position:relative;">

                    </div>
                    <input type="hidden" name="crop" id="crop">
                </div>
                
                <div class="data" style="width:30%;">
                   <label for="variety">Crop Variety</label>
                   <input type="text" name="variety" id="variety" required>
                </div>
                <div class="data" style="width:30%;">
                   <label for="start_date">Start Date</label>
                   <input type="date" name="start_date" id="start_date" required>
                </div>
                <div class="data" style="width:30%;">
                   <label for="harvest">Expected Harvest Date</label>
                   <input type="date" name="harvest" id="harvest" required>
                </div>
                <div class="data" style="width:30%;">
                   <label for="harvest">Expected Yield</label>
                   <input type="number" name="yield" id="yield" required>
                </div>
                <div class="data" style="width:60%;">
                   <label for="harvest">Other Notes</label>
                   <input type="text" name="notes" id="notes">
                </div>
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="addCropCycle()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
