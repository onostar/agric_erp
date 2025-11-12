<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $task = strtoupper(htmlspecialchars(stripslashes($_POST['task_title'])));
    $workers = strtoupper(htmlspecialchars(stripslashes($_POST['workers'])));
    /* $description = ucwords(htmlspecialchars(stripslashes($_POST['description'])));
    $labour_cost = htmlspecialchars(stripslashes($_POST['labour_cost'])); */
    $start = htmlspecialchars(stripslashes($_POST['start_date']));
    // $end = htmlspecialchars(stripslashes($_POST['end_date']));
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
    /* if($labour_cost == 0 || $labour_cost == ""){
        $status = 1;
    }else{
        $status = 0;
    } */
    $data = array(
        "task_number" => $task_no,
        "cycle" => $cycle,
        "farm" => $farm,
        "field" => $field,
        "title" => $task,
        "task_type" => "Crop Cycle",
        // "description" => $description,
        "workers" => $workers,
        // "labour_cost" => $labour_cost,
        "start_date" => $start,
        // "end_date" => $end,
        // "payment_status" => $status,
        "done_by" => $user,
        "post_date" => $date
    );
    //check if there is an harvest done for this cycle and task is pruning or sucker management
    $harvests = $get_details->fetch_count_cond('harvests', 'cycle', $cycle);
    if($harvests <= 0 && $task == "PRUNING"){
        echo "<p class='exist' style='background:red;color:#fff'>Harvest has not been recorded for this crop cycle. Cannot start the selected task.</p>";
        // echo "<script>$('.exist').fadeOut(5000);</script>";
        echo "<script>alert('Harvest has NOT been recorded for this crop cycle. Cannot start the selected task.')</script>";
        exit();
    }else{
       
    //check if there is an ongoing task in this crop cycle
    $check = $get_details->fetch_count_2cond('tasks', 'cycle', $cycle, 'task_status', 0);
    if($check > 0){
        echo "<p class='exist' style='background:red;color:#fff'>There is an ongoing task in this crop cycle. Please complete it before adding another task.</p>";
        echo "<script>$('.exist').fadeOut(5000);</script>";
        echo "<script>alert('There is an ongoing task in this crop cycle. Please complete it before adding another task.')</script>";
        exit();
    }else{
        //proceed to add task
        $add_task = new add_data("tasks", $data);
        $add_task->create_data();
        if($add_task){
            //check if task is induction and update expected harvest date of cycle
            if($task == "INDUCTION"){
                //get induction date
                $induction_date = date("Y-m-d", strtotime($start));
                //calculate expected harvest date (6 months from induction date)
                $expected_harvest = date("Y-m-d", strtotime("+5 months", strtotime($induction_date)));
                //update expected harvest date of cycle
                $update_cycle = new update_table();
                $update_cycle->update('crop_cycles', 'expected_harvest', 'cycle_id', $expected_harvest, $cycle);
            }
           
            //get last inserted task
            /* $ids = $get_details->fetch_lastInserted('tasks', 'task_id');
            $task_id = $ids->task_id; */
            
        ?>
        <div class="success"><p><?php echo $task?>  Started Successfully <i class="fas fa-thumbs-up"></i></p></div>
<?php } } }?>
    
