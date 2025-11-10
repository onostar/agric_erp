<div id="cycles">
<style>
    .nomenclature .profile_foto{
    width:18%;
    height:150px;
 }
 .nomenclature .inputs{
    width:75%;
    display:flex;
    flex-wrap: wrap;
    gap:.8rem;
 }
 .nomenclature .inputs .data{
    width:30%;
 }
 .nomenclature .inputs .data label{
    text-transform: capitalize;
    color:#222;
    width:35%;
 }
 .nomenclature .inputs .data input{
   
    padding:5px;
 }
 #main_consult textarea{
    width:100%;
    min-height:70px;
    padding:5px;
 }
 .tasks_done{
    margin-bottom:5px!important;
    /* border-bottom:1px solid #b3b3b3ff!important; */
    padding:0 20px 0!important;
 }
 .ongoing{
    display: flex;
    flex-wrap: wrap;
    gap: .2rem;
 }
 .ongoing .allResults{
    width: 43%!important;
    margin:0!important;
 }
 .ongoing .addUserForm{
    width: 53%!important;
    margin:0!important;
    border-right: 1px solid #ccc;

 }
 .ongoing .allResults table#data_table td{
    padding:5px;
    font-size: .75rem;
 }
 @media screen and (max-width: 768px){
    .ongoing{
        flex-direction: column;
    }
    .ongoing .allResults, .ongoing .addUserForm{
        width: 100%!important;
    }
 }
 </style>
<?php
    session_start();
    
    $store = $_SESSION['store_id'];
    $role = $_SESSION['role'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['cycle'])){
            $cycle = $_GET['cycle'];
            //get cycle details
            $get_visits = new selects();
            $adms = $get_visits->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
            foreach($adms as $adm){
                $field = $adm->field;
                $crop = $adm->crop;
                $variety = $adm->variety;
                $start = $adm->start_date;
                $end = $adm->expected_harvest;
                $area = $adm->area_used;
                $note = $adm->notes;
                $cycle_status = $adm->cycle_status;
                $created_by = $adm->created_by;
                $yield = $adm->expected_yield;
            }
            //get field details
            $vis = $get_visits->fetch_details_cond('fields', 'field_id', $field);
            foreach($vis as $vis){
                $field_name = $vis->field_name;
                $soil_type = $vis->soil_type;
            }

            
            //get item name
            /* $rows = $get_visits->fetch_details_cond('items', 'item_id', $crop);
            foreach($rows as $row){
                $crop_name = $row->item_name;
            } */
?>
        <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed;"href="javascript:void(0)" onclick="showPage('crop_cycle.php')"><i class="fas fa-close"></i> Close</a>

    <div id="patient_details">
        <h3 style="background:var(--tertiaryColor); color:#fff">Crop Cycle CY0<?php echo $cycle?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="nomenclature">
            <!-- <div class="profile_foto" style="width:22%; margin:0 10px 0 0">
                <img src="<?php echo '../photos/'.$row->photo?>" alt="Photo">
            </div> -->
            <div class="inputs" style="width:100%">
                
                <div class="data">
                    <label for="prn">Field:</label>
                    <input type="text"value="<?php echo $field_name?>" readonly>
                   
                </div>
                <div class="data">
                    <label for="phone_number">Area used:</label>
                    <input type="text" required value="<?php echo $area?>Hec" readonly>
                </div>
                <!-- <div class="data">
                    <label for="phone_number">Crop Variety:</label>
                    <input type="text" required value="<?php echo $variety?>" readonly>
                </div> -->
                
                <div class="data">
                    <label for="customer_store">Start Date:</label>
                    <input type="text" value="<?php echo date("d-M-Y", strtotime($start))?>">
                </div>
                <div class="data">
                    <label for="phone_number">Expected Harvest Date:</label>
                    <input type="text" required value="<?php echo date("d-M-Y", strtotime($end))?>" readonly>
                </div>
                <div class="data">
                    <label for="phone_number">Expected yield:</label>
                    <input type="text" required value="<?php echo $yield?>" readonly style="color:green">
                </div>
                <div class="data">
                    <label for="phone_number">Days Remaining:</label>
                    <?php
                        $date = new DateTime($end);
                        $now = new DateTime();
                        $interval = $date->diff($now);
                        $days_remaining = $interval->days;
                    ?>
                    <input type="text" style="color:var(--secondaryColor)" required value="<?php echo $days_remaining.' days'; ?>" readonly>
                </div>
                <div class="data">
                    <?php
                        //get created by
                        $crs = $get_visits->fetch_details_group('users', 'full_name', 'user_id', $created_by);
                        $creator = $crs->full_name;
                    ?>
                    <label for="phone_number">Created By:</label>
                    <input type="text" required value="<?php echo $creator?>" readonly>
                </div>
                <div class="data" style="width:32%">
                    <label for="gender">Status:</label>
                    <?php
                        if($cycle_status == 0){
                            $status =  "Ongoing";
                        }elseif($cycle_status == -1){
                            $status =  "Abandoned";
                        }else{
                            $status =  "Completed";
                        } 
                    ?>
                    <input type="text" value="<?php echo $status?>">
                </div>
                <div class="data">
                    <label for="customer_store">Cost Incurred:</label>
                    <?php
                        //get total cost of items used in this cycle
                        $itm_costs = $get_visits->fetch_sum_single('task_items', 'total_cost', 'cycle', $cycle);
                        if(is_array($itm_costs)){
                            foreach($itm_costs as $itm_cost){
                                $item_cost = $itm_cost->total;
                            }
                        }else{
                            $item_cost = 0;
                        }
                        //get total labour cost for this cycle
                        $lab_costs = $get_visits->fetch_sum_single('tasks', 'labour_cost', 'cycle', $cycle);
                        if(is_array($lab_costs)){
                            foreach($lab_costs as $lab_cost){
                                $labs_cost = $lab_cost->total;
                            }
                        }else{
                            $labs_cost = 0;
                        }
                        $cost_incurred = $item_cost + $labs_cost;
                    ?>
                    <input type="text" value="<?php echo "₦".number_format($cost_incurred, 2)?>" style="color:var(--secondaryColor)" readonly>
                </div>
                
            </div>
        </section>
        <section id="allergy" style="width:auto; background:transparent; box-shadow:none; margin:10px 0">
            <?php
                //check if land preparation has been done
                $ldps = $get_visits->fetch_details_2cond('tasks', 'cycle', 'title', $cycle, 'LAND PREPARATION');
                if(is_array($ldps)){
                    foreach($ldps as $ldp){
                        $task_status = $ldp->task_status;
                    }
                }else{
                    $task_status = -1;
                }
                //check for planting task
                $pts = $get_visits->fetch_details_2cond('tasks', 'cycle', 'title', $cycle, 'PLANTING');
                if(is_array($pts)){
                    foreach($pts as $pt){
                        $plant_status = $pt->task_status;
                    }
                }else{
                    $plant_status = -1;
                }
                //meaning no land preparation started yet
                if($task_status == -1){
            ?>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('land_preparation.php?cycle=<?php echo $cycle?>')">Start Land Preparation <i class="fas fa-landmark"></i></button>
            <?php }
            //if land preparation has been completed, allow adding of other tasks
            if($task_status == 1){?>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('add_cycle_task.php?cycle=<?php echo $cycle?>')">Add Task <i class="fas fa-tasks"></i></button>
            <?php }?>
            
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('add_observation.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Add Observations <i class="fas fa-pen-clip"></i></button>
            <?php
            //if planting has been completed, allow harvest
            if($plant_status == 1){?>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('start_harvest_crop.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Harvest Crop <i class="fas fa-seedling"></i></button>
            <?php }?>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="closeCycle('<?php echo $cycle?>')" title="complete crop cycle">Close Cycle <i class="fas fa-check-double"></i></button>
            <button style="background:#dfdfdf; border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="abandonCycle('<?php echo $cycle?>')" title="Abandon crop cycle">Abandon Cycle <i class="fas fa-close"></i></button>

        </section>
        
        <!-- check for on going tasks -->
        <?php
            $tasks = $get_visits->fetch_details_2cond('tasks', 'cycle', 'task_status', $cycle, 0);
            if(is_array($tasks)){
                foreach($tasks as $task){
                    $task_id = $task->task_id;
                    $title = $task->title;
                    $notes = $task->description;
                    $start_date = $task->start_date;
                    $workers = $task->workers;
                    $labour_cost = $task->labour_cost;
                    $posted_by = $task->done_by;
                    $date_posted = $task->post_date;
                }
        ?>
        <section id="main_consult">
            <div class="add_user_form" style="width:100%; margin:0!important">
                <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor)"><?php echo $title?> currently on going</h3>
                <!-- <form method="POST" id="addUserForm"> -->
                <div class="ongoing">
                <section class="addUserForm" style="padding:10px!important; margin:0!important">
                    <div class="consultant" style="display:flex; gap:1rem; flex-wrap:wrap; padding:10px; margin-bottom:0">
                        <?php
                            //get consultant name
                            $cons = $get_visits->fetch_details_cond('users', 'user_id', $posted_by);
                            foreach($cons as $con){
                                $posted = $con->full_name;
                            };
                            
                        ?>
                        <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($date_posted))?></span></p>
                        <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $posted?></span></p>
                        <p>Started on: <span style="color:brown; text-transform:uppercase"><?php echo date("d-M-Y, H:ia", strtotime($start_date))?></span></p>
                    </div>
                    <div class="inputs" style="gap:.5rem">
                        <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id?>">
                        <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                        <div class="data" style="width:49%!important">
                            <label for="description">Notes/Observations</label>
                            <textarea name="description" id="description" placeholder="Brief description of task done with observations" ><?php echo $notes?></textarea>
                        </div>
                        <div class="data" style="width:49%!important">
                            <label for="description">Assigned Workers</label>
                            <textarea name="workers" id="workers" placeholder="Input Names of Workers involved in this task (seperate by a comma)" ><?php echo $workers?></textarea>
                        </div>
                        
                        <div class="data" style="width:auto!important">
                            <button type="button" id="add_cat" name="add_cat" style="font-size:.8rem; padding:7px" onclick="updateCycleTask()">Update <i class="fas fa-layer-group"></i></button>
                            <a style="border-radius:15px; background:#dfdfdf;color:#222; padding:6px; box-shadow:1px 1px 1px #222; border:1px solid #fff" title="add items used for task" href="javascript:void(0)" onclick="showForm('add_cycle_task_items.php?task_id=<?php echo $task_id?>&cycle=<?php echo $cycle?>')">Add Items <i class="fas fa-plus-square"></i></a>
                            <a style="border-radius:15px; background:#dfdfdf;color:#222;padding:6px; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showForm('end_task.php?task=<?php echo $task_id?>')">End Task <i class="fas fa-close"></i></a>
                        </div>
                    </div>
                    
                </section> 
                <div class="allResults" style="margin:10px!important">
                    <h2 style="font-size:.9rem; text-align:center;">Items Used for task</h2>
                    <table id="data_table" class="searchTable">
                        <thead>
                            <tr style="background:transparent; color:#222">
                                <td>S/N</td>
                                <td>Item</td>
                                <td>Qty</td>
                                <td>Posted By</td>
                                <td>Post Date</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                                $n = 1;
                                $rows = $get_visits->fetch_details_cond('task_items', 'task_id', $task_id);
                                if(gettype($rows) === 'array'){
                                foreach($rows as $row):
                            ?>
                            <tr>
                                <td style="text-align:center; color:red;"><?php echo $n?></td>
                                <td>
                                    <?php 
                                        $str = $get_visits->fetch_details_group('items', 'item_name', 'item_id', $row->item);
                                        echo $str->item_name;
                                    ?>
                                </td>
                                <td style="color:green; text-align:center">
                                    <?php 
                                        echo $row->quantity;
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        //get posted by
                                        $posted_by = $get_visits->fetch_details_group('users', 'full_name', 'user_id', $row->posted_by);
                                        echo $posted_by->full_name;
                                    ?>
                                </td>
                                <td style="color:var(--primaryColor)"><?php echo date("d-m-Y, h:ia", strtotime($row->post_date))?></td>
                            </tr>
                            
                            <?php $n++; endforeach;}?>
                        </tbody>
                    </table>
                    
                    <?php
                        if(gettype($rows) == "string"){
                            echo "<p class='no_result'>'No item found'</p>";
                        }
                    
                    ?>
                </div>
                </div>
            </div>
        </section>
        <?php }?>
        
        <section id="all_forms">

        </section>
        <div class="tasks_notes">
            <section id="main_consult" class="tasks notes">
                <h3 style="background:var(--primaryColor)">Tasks Done</h3>
                <?php
                    //tasks done
                    $tsks = $get_visits->fetch_details_cond('tasks', 'cycle', $cycle);
                    if(is_array($tsks)){
                        foreach($tsks as $tsk){
                    
                ?>
                <div id="tasks_done">
                    <div class="consultant" style="display:flex; gap:1rem; flex-wrap:wrap; padding:5px; margin-bottom:0">
                        <?php
                            //get consultant name
                            $cons = $get_visits->fetch_details_cond('users', 'user_id', $tsk->done_by);
                            foreach($cons as $con){
                                $done_by = $con->full_name;
                            };
                           
                        ?>
                        <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($tsk->post_date))?></span></p>
                        <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
                        <p>Started on: <span style="color:brown; text-transform:uppercase"><?php echo date("d-M-Y, H:ia", strtotime($tsk->start_date))?></span></p>
                        <?php if($tsk->task_status == 1){?>
                        <p>Ended: <span style="color:brown; text-transform:uppercase"><?php echo date("d-M-Y, H:ia", strtotime($tsk->end_date))?></span></p>
                        <?php
                        //get days used for the task
                            $ended = new DateTime($tsk->end_date);
                            $started = new DateTime($tsk->start_date);
                            $interval = $ended->diff($started);
                            $days_used = $interval->days;
                            if($days_used < 1){
                                $days_used = 1;
                            }
                        ?>
                        <p>Days used: <span style="color:brown; text-transform:uppercase"><?php echo $days_used.' days'; ?></span></p>
                        <?php }else{?>
                        <p>Status: <span style="color:brown;">Ongoing <i class="fas fa-spinner"></i></span></p>
                        <?php
                        //get days used for the task so far
                            $started = new DateTime($tsk->start_date);
                            $now = new DateTime();
                            $interval = $now->diff($started);
                            $days_gone = $interval->days;
                            if($days_gone < 1){
                                $days_gone = 1;
                            }
                        ?>
                        <p>Days ongoing: <span style="color:brown; "><?php echo $days_gone.' day(s)'; ?></span></p>
                        <?php }?>
                    </div>
                    <form>
                        <div class="inputs">
                            <div class="data" style="width:100%!important; margin-top:0!important">
                                <!-- <label for="notes" style="background:none; color:#000; text-align:left">Task</label> -->
                                <input type="text" readonly value="<?php echo $tsk->title.' ('.$tsk->task_number.')'?>" style="border:1px solid #cdcdcd!important; background:#cdcdcd">
                            </div>
                            
                            <div class="data" style="width:48.5%!important">
                                <label for="notes">Description/Notes</label>
                                <textarea name="note" id="note" readonly style="min-height:100px"><?php echo $tsk->description?></textarea>
                            </div>
                            <div class="data" style="width:48.5%!important">
                                <label for="notes">Assigned Workers</label>
                                <textarea name="workers" id="workers" readonly style="min-height:100px"><?php echo $tsk->workers?></textarea>
                            </div>
                           
                        </div>
                    </form>
                    <div class="allResults" style="width:100%!important;margin:0!important">
                        <h4 style="background:var(--otherColor); color:#fff; padding:5px">Items Used for this task</h4>
                        <table id="data_table" class="searchTable">
                            <thead>
                                <tr style="background:transparent!important; color:#000!important;">
                                    <td>S/N</td>
                                    <td>Item</td>
                                    <td>Qty</td>
                                    <?php if($role == "Admin" || $role == "Accountant"){?>
                                    <td>Unit Cost</td>
                                    <td>Total</td>
                                    <?php }?>
                                    <td>Date</td>
                                    <td>Posted by</td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $m = 1;
                                    $items = $get_visits->fetch_details_cond('task_items', 'task_id', $tsk->task_id);
                                    if(gettype($items) === 'array'){
                                    foreach($items as $item):
                                ?>
                                <tr>
                                    <td style="text-align:center; color:red;"><?php echo $m?></td>
                                    <td style="color:var(--moreColor)">
                                        <?php 
                                            $str = $get_visits->fetch_details_group('items', 'item_name', 'item_id', $item->item);
                                            echo $str->item_name;
                                        ?>
                                    </td>
                                    <td><?php echo $item->quantity?></td>
                                    <?php if($role == "Admin" || $role == "Accountant"){?>
                                    <td><?php echo number_format($item->unit_cost,2)?></td>
                                    <td><?php echo number_format($item->total_cost,2)?></td>
                                    <?php } ?>
                                    <td><?php echo date("d-m-Y h:ia", strtotime($item->post_date));?></td>
                                    <td>
                                        <?php
                                            //get posted by
                                            $checks = $get_visits->fetch_details_cond('users',  'user_id', $item->posted_by);
                                            foreach($checks as $check){
                                                $full_name = $check->full_name;
                                            }
                                            echo $full_name;
                                        ?>
                                    </td>
                                    
                                </tr>
                                <?php $m++; endforeach;}?>
                            </tbody>
                        </table>
                        <?php
                            if(is_array($items)){
                                if($role == "Admin" || $role == "Accountant"){
                                    //get total cost of items used
                                    $totals = $get_visits->fetch_sum_single('task_items', 'total_cost', 'task_id', $tsk->task_id);
                                    if(is_array($totals)){
                                        foreach($totals as $tot){
                                            $total_cost = $tot->total;
                                        }
                                    }else{
                                        $total_cost = 0;
                                    }
                                    echo "<h4 style='text-align:right; padding:5px; background:var(--tertiaryColor); color:#fff;'>Total Cost of items used: ₦".number_format($total_cost, 2)."</h4>";
                                    
                                }
                            }else{
                                echo "<p style='font-size:.8rem;' class='no_result'>'No farm input used'</p>";
                            }
                        
                        ?>
                    </div>
                    <?php if($role == "Admin" || $role == "Accountant"){?>
                    <!-- labour cost -->
                    <p style="text-align:right; margin:10px 0; font-weight:bold; color:#222">Labour Cost: ₦<?php echo number_format($tsk->labour_cost, 2)?></p>
                    <hr>
                    <?php
                    //get total cost of items used
                    $totals = $get_visits->fetch_sum_single('task_items', 'total_cost', 'task_id', $tsk->task_id);
                    if(is_array($totals)){
                        foreach($totals as $tot){
                            $total_cost = $tot->total;
                        }
                    }else{
                        $total_cost = 0;
                    }
                    ?>
                    <p style="text-align:right; margin:10px 0; font-weight:bold; color:green">Total Cost (Items + Labour): ₦<?php echo number_format($total_cost + $tsk->labour_cost, 2)?></p>
                    <?php }?>
                </div>
                
                <?php }}?>
            </section>
            <section id="main_consult" class="observations notes">
                <h3 style="background:var(--primaryColor)">Remarks/Observations</h3>
                <?php
                    //tasks done
                    $tsks = $get_visits->fetch_details_cond('observations', 'cycle', $cycle);
                    if(is_array($tsks)){
                        foreach($tsks as $tsk){
                    
                ?>
                <div id="tasks_done">
                    <div class="consultant" style="display:flex; gap:1rem; flex-wrap:wrap; padding:5px; margin-bottom:10px">
                        <?php
                            //get consultant name
                            $cons = $get_visits->fetch_details_cond('users', 'user_id', $tsk->done_by);
                            foreach($cons as $con){
                                $done_by = $con->full_name;
                            };

                        ?>
                        <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($tsk->post_date))?></span></p>
                        <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
                    </div>
                    <form>
                        <div class="inputs">
                            
                            <div class="data" style="width:100%!important">
                                <label for="notes">Description</label>
                                <textarea name="note" id="note" readonly><?php echo $tsk->description?></textarea>
                            </div>
                            
                        </div>
                    </form>
                </div>
                <?php }}?>
            </section>

        </div>
        
       <section id="last_consult">
            <h3>Harvests</h3>
            <div class="displays allResults new_data" style="width:100%!important;margin:0!important">
                <table id="data_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--primaryColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Quantity</td>
                            <td>Unit Cost</td>
                            <td>Total Cost</td>
                            <td>Posted by</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $get_users = new selects();
                            $details = $get_users->fetch_details_cond('harvests', 'cycle', $cycle);
                            if(gettype($details) === 'array'){
                            foreach($details as $detail):
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td style="color:var(--moreColor)"><?php echo date("d-m-Y h:ia", strtotime($detail->post_date));?></td>
                            <td><?php echo $detail->quantity ?></td>
                            <td><?php echo "₦".number_format($detail->unit_cost, 2);?></td>
                            <td style="color:red">
                                <?php
                                    //get total cost
                                    $costs = $get_users->fetch_sum_2colCond('harvests', 'quantity', 'unit_cost', 'harvest_id', $detail->harvest_id);
                                    if(is_array($costs)){
                                        foreach($costs as $cost){
                                            echo "₦".number_format($cost->total, 2);
                                        }
                                    }else{
                                        echo "₦0.00";
                                    }
                                ?>
                            </td>
                            <td>
                                <?php
                                    //get posted by
                                    $get_posted_by = new selects();
                                    $checks = $get_posted_by->fetch_details_cond('users',  'user_id', $detail->posted_by);
                                    foreach($checks as $check){
                                        $full_name = $check->full_name;
                                    }
                                    echo $full_name;
                                ?>
                            </td>
                            
                        </tr>
                        <?php $n++; endforeach;}?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
        
<?php
            
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>
