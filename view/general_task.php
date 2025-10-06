<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    $farm = $_SESSION['store_id'];
?>

<div id="general_tasks" class="displays">
    <div class="info" style="width:35%; margin:20px"></div>
    <div class="add_user_form" style="width:50%; margin:20px">
        <h3 style="background:var(--otherColor)">Post Field maintenance / Task Done</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:.8rem">
                <div class="data" style="width:100%">
                    <label for="field">Farm Field</label>
                    <select name="field" id="field">
                        <option value="" selected disabled>Select Field</option>
                        <?php
                            //get field
                            $get_details = new selects();
                            $rows = $get_details->fetch_details_2cond('fields', 'farm', 'field_status', $farm, 0);
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->field_id?>"><?php echo $row->field_name?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" style="width:100%">
                    <label for="task_title">Task title</label>
                    <input type="text" name="task_title" id="task_title" placeholder="Input task title" required>
                </div>
                <div class="data" style="width:100%">
                    <label for="description">Notes/Observations</label>
                    <textarea name="description" id="description" placeholder="Brief description of task done with observations" required></textarea>
                </div>
                <div class="data" style="width:100%">
                    <label for="description">Assigned Workers</label>
                    <textarea name="workers" id="workers" placeholder="Input Names of Workers involved in this task (seperate by a comma)" required></textarea>
                </div>
                <div class="data" style="width:50%">
                    <label for="labour_cost">Labour Cost (NGN)</label>
                    <input type="number" name="labour_cost" id="labour_cost" value="0" placeholder="Input labour cost for this task" required>
                </div>
                <div class="data" style="width:auto">
                    <button type="submit" id="add_cat" name="add_cat" onclick="addTask()">Save <i class="fas fa-layer-group"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
