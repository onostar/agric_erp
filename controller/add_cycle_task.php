<?php
   
    
    if(isset($_GET['cycle'])){
        $cycle = $_GET['cycle'];
        // $crop = $_GET['crop'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get crop name
   /*  $get_cycs = new selects();
    $names = $get_cycs->fetch_details_group('items', 'item_name', 'item_id', $crop);
    $crop_item = $names->item_name; */
?>  <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px">
        <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor)">Add Task Done For CY0<?php echo $cycle?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:1rem">
                <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                <div class="data" style="width:48%">
                    <label for="task_title">Task</label>
                    <select name="task_title" id="task_title">
                        <option value="" disabled selected>Select Task</option>
                        <option value="PLANTING">PLANTING</option>
                        <option value="FERTILIZER APPLICATION">FERTILIZER APPLICATION</option>
                        <option value="WEEDING">WEEDING</option>
                        <option value="INDUCTION">INDUCTION</option>
                        <option value="PRUNING">PRUNING</option>
                        <option value="SUCKER REMOVAL">SUCKER REMOVAL</option>
                    </select>
                </div>
                <div class="data" style="width:48%">
                    <label for="start_date">Start date</label>
                    <input type="datetime-local" name="start_date" id="start_date" value="<?php echo date("Y-m-d H:i")?>" required>
                </div>
                <!-- <div class="data" style="width:48%">
                    <label for="end_date">End date</label>
                    <input type="datetime-local" name="end_date" id="end_date" required>
                </div> -->
                <!-- <div class="data" style="width:100%">
                    <label for="description">Notes</label>
                    <textarea name="description" id="description" placeholder="Brief description of task done with observations" required></textarea>
                </div> -->
                <div class="data" style="width:100%">
                    <label for="description">Assigned Workers</label>
                    <textarea name="workers" id="workers" placeholder="Input Names of Workers involved in this task (seperate by a comma)" required></textarea>
                </div>
                <!-- <div class="data" style="width:50%">
                    <label for="labour_cost">Labour Cost (NGN)</label>
                    <input type="number" name="labour_cost" id="labour_cost" value="0" placeholder="Input labour cost for this task" required>
                </div> -->
                <div class="data" style="width:auto">
                    <button type="type" id="add_cat" name="add_cat" onclick="addCycleTask()">Save <i class="fas fa-layer-group"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="closeAllForms()"><i class="fas fa-close"></i> Close</a>
                </div>
            </div>
            
        </section>    
    </div>

    <?php }?>