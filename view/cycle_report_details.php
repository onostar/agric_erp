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
    height:50px;
    padding:5px;
 }
 .tasks_done{
    margin-bottom:5px!important;
    /* border-bottom:1px solid #b3b3b3ff!important; */
    padding:0 20px 0!important;
 }
 .notes{
    max-height:500px;
    overflow-y:auto;
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
                $closed_on = $adm->end_date;
                $closed_by = $adm->ended_by;
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
        <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed;"href="javascript:void(0)" onclick="showPage('crop_cycle_report.php')"><i class="fas fa-close"></i> Close</a>

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
                    <?php
                        /* $date = new DateTime($start);
                        $closed = new DateTime($closed_on);
                        $interval = $closed->diff($date); */
                    ?>
                    <input type="text" value="<?php echo date("d-M-Y", strtotime($start));?>">
                </div>
                <div class="data">
                    <label for="phone_number">Expected Harvest Date:</label>
                    <?php
                        if($end == "0000-00-00" || $end == NULL){
                            $harvest_date = "N/A";
                        }else{
                            $harvest_date = date("d-M-Y", strtotime($end));
                        }
                    ?>
                    <input type="text" required value="<?php echo $harvest_date?>" readonly>
                </div>
                <div class="data">
                    <label for="phone_number">Expected yield:</label>
                    <input type="text" required value="<?php echo $yield?>" readonly style="color:green">
                </div>
                <?php
                    if($cycle_status != 0){
                ?>
                <div class="data">
                    <label for="phone_number">Days Completed:</label>
                    <?php
                        $date = new DateTime($start);
                        $closed = new DateTime($closed_on);
                        $interval = $closed->diff($date);
                        $days_done = $interval->days;
                    ?>
                    <input type="text" style="color:var(--tertiaryColor)" required value="<?php echo $days_done.' days'; ?>" readonly>
                </div>
                <?php }else{?>
                <div class="data">
                    <label for="phone_number">Days To harvest:</label>
                    <?php
                        if($harvest_date == "N/A"){
                            $days_remaining = "N/A";
                        }else{
                            $date = new DateTime($harvest_date);
                            $now = new DateTime();
                            $interval = $date->diff($now);
                            $days_remaining = $interval->days;
                            if($days_remaining < 0){
                                $days_remaining = 0;
                            }
                        }
                    ?>
                    <input type="text" style="color:var(--secondaryColor)" required value="<?php echo $days_remaining.' days'; ?>" readonly>
                </div>
                <?php }?>
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
                <?php
                    if($cycle_status != 0){
                ?>
                <div class="data">
                    <label for="closed" style="color:var(--secondaryColor)">Date CLosed:</label>
                    <input type="text" required value="<?php echo date("d-M-Y", strtotime($closed_on))?>" readonly>
                </div>
                <div class="data">
                    <?php
                        //get closed by
                        $checks = $get_visits->fetch_details_cond('users',  'user_id', $closed_by);
                        foreach($checks as $check){
                            $ended_by = $check->full_name;
                        }
                    ?>
                    <label for="closed" style="color:var(--moreColor)">Closed By:</label>
                    <input type="text" required value="<?php echo $ended_by?>" readonly>
                </div>
                <?php }?>
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
                            <td>Qty (kg)</td>
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
                            <td style="text-align:center; color:green"><?php echo $detail->quantity ?></td>
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
        <!-- crop removals -->
       <section id="last_consult">
            <h3>Crop Removals</h3>
            <div class="displays allResults new_data" style="width:100%!important;margin:0!important">
                <table id="data_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--moreColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Qty (kg)</td>
                            <td>Reason</td>
                            <td>Other Details</td>
                            <td>Removed By</td>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $removes = $get_users->fetch_details_cond('crop_removal', 'cycle', $cycle);
                            if(gettype($removes) === 'array'){
                            foreach($removes as $remove):
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td style="color:var(--moreColor)"><?php echo date("d-M-Y h:ia", strtotime($remove->date_removed));?></td>
                            <td style="text-align:center;color:green"><?php echo $remove->quantity ?></td>
                            <td><?php echo $remove->reason ?></td>
                            <td><?php echo $remove->other_notes ?></td>
                            <td>
                                <?php
                                    //get posted by
                                    $checks = $get_visits->fetch_details_cond('users',  'user_id', $remove->removed_by);
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
        <!-- sucker removals -->
       <section id="last_consult">
            <h3>Suckers Removed</h3>
            <div class="displays allResults new_data" style="width:100%!important;margin:0!important">
                <table id="data_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--primaryColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Qty (kg)</td>
                            <td>Removed By</td>
                            
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $sucks = $get_users->fetch_details_cond('sucker_removal', 'cycle', $cycle);
                            if(gettype($sucks) === 'array'){
                            foreach($sucks as $suck):
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td style="color:var(--moreColor)"><?php echo date("d-M-Y h:ia", strtotime($suck->date_removed));?></td>
                            <td style="text-align:center;color:green"><?php echo $suck->quantity ?></td>
                            <td>
                                <?php
                                    //get posted by
                                    $checks = $get_visits->fetch_details_cond('users',  'user_id', $suck->removed_by);
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
