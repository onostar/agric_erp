<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $field = htmlspecialchars(stripslashes($_POST['field']));
    $task = strtoupper(htmlspecialchars(stripslashes($_POST['task_title'])));
    $workers = strtoupper(htmlspecialchars(stripslashes($_POST['workers'])));
    /* $description = ucwords(htmlspecialchars(stripslashes($_POST['description'])));
    $labour_cost = htmlspecialchars(stripslashes($_POST['labour_cost'])); */
    $start = htmlspecialchars(stripslashes($_POST['start_date']));
    // $end = htmlspecialchars(stripslashes($_POST['end_date']));
    $farm = $_SESSION['store_id'];
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $get_details = new selects();
    //get field name
    $fields = $get_details->fetch_details_cond('fields', 'field_id', $field);
    if(is_array($fields)){
        foreach($fields as $field_row){
            $field_name = $field_row->field_name;
        }
    }
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
   /*  if($labour_cost == 0 || $labour_cost == ""){
        $status = 1;
    }else{
        $status = 0;
    } */
    $data = array(
        "task_number" => $task_no,
        // "cycle" => $cycle,
        "farm" => $farm,
        "field" => $field,
        "title" => $task,
        "task_type" => "General Maintenance",
        // "description" => $description,
        "workers" => $workers,
        // "labour_cost" => $labour_cost,
        // "payment_status" => $status,
        "start_date" => $start,
        // "end_date" => $end,
        "done_by" => $user,
        "post_date" => $date
    );
    //check if there is an ongoing similar task on the field
    $task_check = $get_details->fetch_count_3cond('tasks', 'field', $field, 'title', $task,'task_status', 0);
    if($task_check > 0){
        ?>
        <script>alert("There is an ongoing <?php echo $task?> task on <?php echo $field_name?> field. You cannot start another one!");</script>
        <div class="exisit"><p style="background: red;color:#fff">There is an ongoing <?php echo $task?> task on <?php echo $field_name?> field. You cannot start another one!</p></div>
        <?php
        exit();
    }else{
    $add_task = new add_data("tasks", $data);
    $add_task->create_data();
    if($add_task){
        //get last inserted task
       /*  $ids = $get_details->fetch_lastInserted('tasks', 'task_id');
        $task_id = $ids->task_id; */
        
    ?>
        <div class="notify"><p><?php echo $task?> Started Successfully on <?php echo $field_name?></p></div>

        
    <div class="displays allResults" id="new_data" style="width:100%!important">
       
    </div>
<?php
    }
}