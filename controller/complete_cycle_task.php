<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $task = strtoupper(htmlspecialchars(stripslashes($_POST['task_title'])));
    $workers = strtoupper(htmlspecialchars(stripslashes($_POST['workers'])));
    $description = ucwords(htmlspecialchars(stripslashes($_POST['description'])));
    // $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get cycle details
    $get_details = new selects();
    $rows = $get_details->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
    foreach($rows as $row){
        $field = $row->field;
        $farm = $row->farm;
    }

    $data = array(
        "cycle" => $cycle,
        "farm" => $farm,
        "field" => $field,
        "title" => $task,
        "description" => $description,
        "workers" => $workers,
        "done_by" => $user,
        "post_date" => $date
    );
    $add_task = new add_data("tasks", $data);
    $add_task->create_data();
    if($add_task){
        //get last inserted task
        $ids = $get_details->fetch_lastInserted('tasks', 'task_id');
        $task_id = $ids->task_id;
        
    ?>
        <div class="notify"><p>Task added Successfully</p></div>

        <div class="add_user_form" style="width:95%; margin:10px">
        <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor)">Add Item used for this task (<?php echo $task?>)</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="display:flex!important; gap:.3rem; flex-wrap:wrap; justify-content:left; align-items:flex-end">
                <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id?>">
                <div class="data" style="width:100%!important; margin:5px 0">
                    <label for="item" style="background:none; color:#000; padding:0;margin:0; text-align:left">Item Used</label>
                    <input type="text" name="item" id="item" style="padding:8px;" required placeholder="Search item" onkeyup="getItemDetails(this.value, 'get_task_items.php')">
                    <div id="sales_item">
                        
                    </div>
                    <input type="hidden" name="task_item" id="task_item">
                </div>
                <div class="data" style="width:30%!important; margin:5px 0">
                    <label for="quantity" style="background:none; color:#000; padding:0; margin:0; text-align:left">Quantity</label>
                    <input type="number" id="quantity" id="quantity" style="padding:8px;">
                </div>
                <div class="data" style="width:auto!important; margin:5px 0!important">
                    <button type="submit" id="add_cat" name="add_cat" onclick="addTaskItem()">Save <i class="fas fa-layer-group"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('cycle_details.php?cycle=<?php echo $cycle?>')"><i class="fas fa-close"></i> Close</a>
                </div>
            </div>
            
        </section>
    </div>
    <div class="displays allResults" id="new_data" style="width:100%!important">
       
    </div>
<?php
    }