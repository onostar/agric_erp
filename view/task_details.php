<div id="cycles">
<style>
   
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
        if(isset($_GET['task'])){
            $task = $_GET['task'];
            //get task details
            $get_visits = new selects();
            $adms = $get_visits->fetch_details_cond('tasks', 'task_id', $task);
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
                $posted = $adm->done_by;
            }
            //get field details
            $vis = $get_visits->fetch_details_cond('fields', 'field_id', $field);
            foreach($vis as $vis){
                $field_name = $vis->field_name;
                $soil_type = $vis->soil_type;
            }

?>
        <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed;"href="javascript:void(0)" onclick="showPage('tasks_done.php')"><i class="fas fa-close"></i> Close</a>

    <div id="patient_details">
        <div class="tasks_notes">
            <section id="main_consult" class="tasks notes" style="width:90%;">
                <h3 style="background:var(--otherColor)">Tasks Details</h3>
                <div id="tasks_done">
                    <div class="consultant" style="display:flex; gap:1rem; flex-wrap:wrap; padding:5px; margin-bottom:0">
                        <?php
                            //get consultant name
                            $cons = $get_visits->fetch_details_cond('users', 'user_id', $posted);
                            foreach($cons as $con){
                                $done_by = $con->full_name;
                            };
                           
                        ?>
                        <p>Field: <span style="color:brown; text-transform:uppercase"><?php echo $field_name?></span></p>
                        <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($date))?></span></p>
                        <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
                        <p>Type: <span style="color:brown; text-transform:uppercase"><?php echo $type?></span></p>
                        
                    </div>
                    <form>
                        <div class="inputs">
                            <div class="data" style="width:100%!important; margin-top:0!important">
                               
                                <input type="text" readonly value="<?php echo $title.' ('.$task_no.')'?>" style="border:1px solid #cdcdcd!important; background:#cdcdcd">
                            </div>
                            
                            <div class="data" style="width:48.5%!important">
                                <label for="notes">Description/Notes</label>
                                <textarea name="note" id="note" readonly style="min-height:100px"><?php echo $description?></textarea>
                            </div>
                            <div class="data" style="width:48.5%!important">
                                <label for="notes">Assigned Workers</label>
                                <textarea name="workers" id="workers" readonly style="min-height:100px"><?php echo $workers?></textarea>
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
                                    $items = $get_visits->fetch_details_cond('task_items', 'task_id', $task);
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
                                    $totals = $get_visits->fetch_sum_single('task_items', 'total_cost', 'task_id', $task);
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
                    <p style="text-align:right; margin:10px 0; font-weight:bold; color:#222">Labour Cost: ₦<?php echo number_format($labour, 2)?></p>
                    <hr>
                    <?php
                    //get total cost of items used
                    $totals = $get_visits->fetch_sum_single('task_items', 'total_cost', 'task_id', $task);
                    if(is_array($totals)){
                        foreach($totals as $tot){
                            $total_cost = $tot->total;
                        }
                    }else{
                        $total_cost = 0;
                    }
                    ?>
                    <p style="text-align:right; margin:10px 0; font-weight:bold; color:green">Total Cost (Items + Labour): ₦<?php echo number_format($total_cost + $labour, 2)?></p>
                    <?php }?>
                </div>
                
               
            </section>
            

        </div>
        
       
    </div>
        
<?php
        } 
        
    }else{

        echo "<p class='no_result'>Session has expired. Please log in again</p>";
        exit();
    }
?>
</div>
