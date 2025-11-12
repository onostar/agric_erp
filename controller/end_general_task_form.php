<?php
   
    
    if(isset($_GET['task'])){
        $task = $_GET['task'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get task details
    $get_cycs = new selects();
    $names = $get_cycs->fetch_details_cond('tasks', 'task_id', $task);
    foreach($names as $name){
        $task_title = $name->title;
    }

        //get task title
?>  <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px 30px!important; padding:10px">
        <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor);color:#fff;">End <?php echo $task_title?> Task</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:1rem">
                <input type="hidden" name="task_id" id="task_id" value="<?php echo $task?>">
                <input type="hidden" name="cycle" id="cycle" value="0">
                
                <div class="data" style="width:48%">
                    <label for="end_date">Date Completed</label>
                    <input type="datetime-local" name="end_date" id="end_date" value="<?php echo date('Y-m-d H:i')?>" required>
                </div>
                
                <div class="data" style="width:48%">
                    <label for="labour_cost">Labour Cost (NGN)</label>
                    <input type="number" name="labour_cost" id="labour_cost" value="0" placeholder="Input labour cost for this task" required>
                </div>
                <div class="data" style="width:auto">
                    <button type="submit" id="add_cat" name="add_cat" onclick="endGeneralTask()">Save <i class="fas fa-layer-group"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; box-shadow:1px 1px 1px #222; border:1px solid #fff"href="javascript:void(0)" onclick="closeAllForms()"><i class="fas fa-close"></i> Close form</a>
                </div>
            </div>
            
        </section>    
    </div>

    <?php }?>