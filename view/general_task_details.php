<div id="cycles">
<style>
   
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
 .ongoing .addUserForm textarea{
    min-height:70px;
 }
 .ongoing .allResults table#data_table td{
    padding:5px;
    font-size: .75rem;
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
        if(isset($_GET['task'])){
            $task_id = $_GET['task'];
            //get task details
            $get_visits = new selects();
            $adms = $get_visits->fetch_details_cond('tasks', 'task_id', $task_id);
            foreach($adms as $adm){
                $field = $adm->field;
                $title = $adm->title;
                $description = $adm->description;
                $workers = $adm->workers;
                $task_no = $adm->task_number;
                $type = $adm->task_type;
                $labour = $adm->labour_cost;
                $status = $adm->payment_status;
                $date = $adm->post_date;
                $start_date = $adm->start_date;
                $end_date = $adm->end_date;
                $posted = $adm->done_by;
            }
            //get field details
            $vis = $get_visits->fetch_details_cond('fields', 'field_id', $field);
            foreach($vis as $vis){
                $field_name = $vis->field_name;
                $soil_type = $vis->soil_type;
            }

?>
        <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed;"href="javascript:void(0)" onclick="showPage('view_general_tasks.php')"><i class="fas fa-close"></i> Close</a>

    <div id="patient_details">
        <div class="tasks_notes" >
            <section id="main_consult" style="width:100%">
                <div class="add_user_form" style="width:100%; margin:0!important">
                    <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--otherColor); text-align:center"><?php echo $title?> details</h3>
                    <!-- <form method="POST" id="addUserForm"> -->
                    <div class="ongoing">
                    <section class="addUserForm" style="padding:10px!important; margin:0!important">
                        <div class="consultant" style="display:flex; gap:1rem; flex-wrap:wrap; padding:10px; margin-bottom:0">
                            <?php
                                //get consultant name
                                $cons = $get_visits->fetch_details_cond('users', 'user_id', $posted);
                                foreach($cons as $con){
                                    $posted = $con->full_name;
                                };
                                
                            ?>
                            <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($date))?></span></p>
                            <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $posted?></span></p>
                            <p>Started on: <span style="color:brown; text-transform:uppercase"><?php echo date("d-M-Y, H:ia", strtotime($start_date))?></span></p>
                        </div>
                        <div class="inputs" style="gap:.5rem">
                            <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id?>">
                            <!-- <input type="hidden" name="cycle" id="cycle" value="0"> -->
                            <div class="data" style="width:49%!important">
                                <label for="description">Notes/Observations</label>
                                <textarea name="description" id="description" placeholder="Brief description of task done with observations" ><?php echo $description?></textarea>
                            </div>
                            <div class="data" style="width:49%!important">
                                <label for="description">Assigned Workers</label>
                                <textarea name="workers" id="workers" placeholder="Input Names of Workers involved in this task (seperate by a comma)" ><?php echo $workers?></textarea>
                            </div>
                            
                            <div class="data" style="width:auto!important">
                                <button type="button" id="add_cat" name="add_cat" style="font-size:.8rem; padding:7px" onclick="updateTask()">Update <i class="fas fa-layer-group"></i></button>
                                <a style="border-radius:15px; background:#dfdfdf;color:#222; padding:6px; box-shadow:1px 1px 1px #222; border:1px solid #fff" title="add items used for task" href="javascript:void(0)" onclick="showForm('add_general_task_item_form.php?task_id=<?php echo $task_id?>')">Add Items <i class="fas fa-plus-square"></i></a>
                                <a style="border-radius:15px; background:#dfdfdf;color:#222;padding:6px; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showForm('end_general_task_form.php?task=<?php echo $task_id?>')">End Task <i class="fas fa-close"></i></a>
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
            </div>
        </section>
            

        </div>
        
       <section id="all_forms">

        </section>
    </div>
        
<?php
        } 
        
    }else{

        echo "<p class='no_result'>Session has expired. Please log in again</p>";
        exit();
    }
?>
</div>
