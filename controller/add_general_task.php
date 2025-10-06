<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $field = htmlspecialchars(stripslashes($_POST['field']));
    $task = strtoupper(htmlspecialchars(stripslashes($_POST['task_title'])));
    $workers = strtoupper(htmlspecialchars(stripslashes($_POST['workers'])));
    $description = ucwords(htmlspecialchars(stripslashes($_POST['description'])));
    $labour_cost = htmlspecialchars(stripslashes($_POST['labour_cost']));
    $farm = $_SESSION['store_id'];
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $get_details = new selects();
   
    //generate receipt invoice
    //get current date
    $todays_date = date("dmyh");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    $task_no = "TK".$farm.$todays_date.$ran_num.$user;
    //check if there is labour cost
    if($labour_cost == 0 || $labour_cost == ""){
        $status = 1;
    }else{
        $status = 0;
    }
    $data = array(
        "task_number" => $task_no,
        // "cycle" => $cycle,
        "farm" => $farm,
        "field" => $field,
        "title" => $task,
        "task_type" => "general maintenance",
        "description" => $description,
        "workers" => $workers,
        "labour_cost" => $labour_cost,
        "payment_status" => $status,
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
        <div class="notify"><p>Task Posted Successfully</p></div>

        <div class="add_user_form" style="width:50%; margin:10px">
        <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor)">Add Item used for this task (<?php echo $task?>)</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="display:flex!important; gap:.3rem; flex-wrap:wrap; justify-content:left; align-items:flex-end">
                <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id?>">
                <div class="data" style="width:100%!important; margin:5px 0">
                    <label for="item" style="background:none; color:#000; padding:0;margin:0; text-align:left">Item Used</label>
                    <input type="text" name="item" id="item" style="padding:8px;" required placeholder="Search item" onkeyup="getItemDetails(this.value, 'get_task_items.php')" autofocus>
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
                    <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('general_task.php')"><i class="fas fa-close"></i> Close</a>
                </div>
            </div>
            
        </section>
    </div>
    <div class="displays allResults" id="new_data" style="width:100%!important">
       
    </div>
<?php
    }