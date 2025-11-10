<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $task = htmlspecialchars(stripslashes($_POST['task_id']));
    $labour_cost = htmlspecialchars(stripslashes($_POST['labour_cost']));
    $end = htmlspecialchars(stripslashes($_POST['end_date']));
   
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    
    //get task details
    $get_details = new selects();
    $rows = $get_details->fetch_details_cond('tasks', 'task_id', $task);
    foreach($rows as $row){
        $task_title = $row->title;
    }
    //check if there is labour cost
    if($labour_cost == 0 || $labour_cost == ""){
        $status = 1;
    }else{
        $status = 0;
    }
    $data = array(
        "labour_cost" => $labour_cost,
        "end_date" => $end,
        "payment_status" => $status,
        "task_status" => 1,
        "ended_by" => $user,
        "end_time" => $date
    );
    $update = new Update_table();
    $update->updateAny('tasks', $data, 'task_id', $task);
   
    if($update){
    ?>
        <div class="success"><p><?php echo $task_title?>  Completed Successfully <i class="fas fa-thumbs-up"></i></p></div>
<?php } ?>
    
