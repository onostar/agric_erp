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
            }
            //get field details
            $vis = $get_visits->fetch_details_cond('fields', 'field_id', $field);
            foreach($vis as $vis){
                $field_name = $vis->field_name;
                $soil_type = $vis->soil_type;
            }

            
            //get item name
            $rows = $get_visits->fetch_details_cond('items', 'item_id', $crop);
            foreach($rows as $row){
                $crop_name = $row->item_name;
            }
?>
        <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed;"href="javascript:void(0)" onclick="showPage('crop_cycle.php')"><i class="fas fa-close"></i> Close</a>

    <div id="patient_details">
        <h3 style="background:var(--tertiaryColor); color:#fff">Crop Cycle for <?php echo $crop_name?></h3>
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
                <div class="data">
                    <label for="phone_number">Crop Variety:</label>
                    <input type="text" required value="<?php echo $variety?>" readonly>
                </div>
                
                <div class="data">
                    <label for="customer_store">Start Date:</label>
                    <input type="text" value="<?php echo date("Y-m-d", strtotime($start))?>">
                </div>
                <div class="data">
                    <label for="phone_number">Expected Harvest Date:</label>
                    <input type="text" required value="<?php echo date("d-M-Y", strtotime($end))?>" readonly>
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
                        $inc_costs = $get_visits->fetch_sum_single('task_items', 'total_cost', 'cycle', $cycle);
                        if(is_array($inc_costs)){
                            foreach($inc_costs as $inc_cost){
                                $cost_incurred = $inc_cost->total;
                            }
                        }else{
                            $cost_incurred = 0;
                        }
                    ?>
                    <input type="text" value="<?php echo "₦".number_format($cost_incurred, 2)?>" style="color:var(--secondaryColor)" readonly>
                </div>
                
            </div>
        </section>
        <section id="allergy" style="width:auto; background:transparent; box-shadow:none; margin:10px 0">
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('add_cycle_task.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Add Task <i class="fas fa-tasks"></i></button>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('add_observation.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Add Observations <i class="fas fa-pen-clip"></i></button>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('start_harvest_crop.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Harvest Crop <i class="fas fa-seedling"></i></button>
            <button style="background:green;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#fff; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="closeCycle('<?php echo $cycle?>')" title="complete crop cycle">Close Cycle <i class="fas fa-check-double"></i></button>
            <button style="background:brown; border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#fff; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="abandonCycle('<?php echo $cycle?>')" title="Abandon crop cycle">Abandon Cycle <i class="fas fa-close"></i></button>

        </section>
        <section id="all_forms">

        </section>
        <section id="last_consult">
            <h3>Harvests</h3>
            <div class="displays allResults new_data" style="width:100%!important;margin:0!important">
                <table id="data_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--primaryColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Quantity</td>
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
                                <!-- <label for="notes" style="background:none; color:#000; text-align:left">Task</label> -->
                                <input type="text" readonly value="<?php echo $tsk->title?>" style="border:1px solid #cdcdcd!important">
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
                                    $items = $get_users->fetch_details_cond('task_items', 'task_id', $tsk->task_id);
                                    if(gettype($items) === 'array'){
                                    foreach($items as $item):
                                ?>
                                <tr>
                                    <td style="text-align:center; color:red;"><?php echo $m?></td>
                                    <td style="color:var(--moreColor)">
                                        <?php 
                                            $str = $get_users->fetch_details_group('items', 'item_name', 'item_id', $item->item);
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
                                            $get_posted_by = new selects();
                                            $checks = $get_posted_by->fetch_details_cond('users',  'user_id', $item->posted_by);
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
                </div>
                <hr>
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
        
       
    </div>
        
<?php
            
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>
