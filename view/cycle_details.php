<div id="patient_consult">
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
    border-bottom:1px solid #cdcdcd!important;

 }
 </style>
<?php
    session_start();
    $store = $_SESSION['store_id'];
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
                    <?php
                        $date = new DateTime($start);
                        $now = new DateTime();
                        $interval = $now->diff($date);
                    ?>
                    <input type="text" value="<?php echo date("Y-m-d", strtotime($start))." (".$interval->days." days)";?>">
                </div>
                <div class="data">
                    <label for="phone_number">Expected Harvest Date:</label>
                    <input type="text" required value="<?php echo date("d-M-Y", strtotime($end))?>" readonly>
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
                
                
            </div>
        </section>
        <section id="allergy" style="width:auto; background:transparent; box-shadow:none; margin:10px 0">
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('add_cycle_task.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Add Task <i class="fas fa-tasks"></i></button>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('add_observation.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Add Observations <i class="fas fa-pen-clip"></i></button>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="showForm('start_harvest_crop.php?cycle=<?php echo $cycle?>&crop=<?php echo $crop?>')">Harvest Crop <i class="fas fa-seedling"></i></button>
            <button style="background:#dfdfdf;border:1px solid #fff; font-size:.8rem; padding:5px 8px; color:#222; box-shadow:1px 1px 1px #222; margin:5px 0" onclick="closeCycle('<?php echo $cycle?>')">Close Cycle <i class="fas fa-close"></i></button>

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
                            //get item name
                            $serv = $get_visits->fetch_details_cond('items', 'item_id', $tsk->item);
                            if(is_array($serv)){
                                foreach($serv as $ser){
                                    $item_name = $ser->item_name;
                                };
                            }else{
                                $item_name = "None";
                            }
                        ?>
                        <p>Done By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
                        <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($tsk->post_date))?></span></p>
                    </div>
                    <form>
                        <div class="inputs">
                            <div class="data" style="width:100%!important">
                                <label for="notes">Task</label>
                                <input type="text" readonly value="<?php echo $tsk->title?>">
                            </div>
                            
                            <div class="data" style="width:100%!important">
                                <label for="notes">Description</label>
                                <textarea name="note" id="note" readonly><?php echo $tsk->description?></textarea>
                            </div>
                            <div class="data" style="width:50%!important">
                                <label for="notes">Item Used</label>
                                <input type="text" id="note" readonly value="<?php echo $item_name?>">
                            </div>
                            <div class="data">
                                <label for="notes">Quantity Used</label>
                                <input type="number" readonly value="<?php echo $tsk->quantity?>">
                            </div>
                        </div>
                    </form>
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
                        <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
                        <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($tsk->post_date))?></span></p>
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
