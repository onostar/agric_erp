<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $task = htmlspecialchars(stripslashes($_POST['task_id']));
    $workers = strtoupper(htmlspecialchars(stripslashes($_POST['workers'])));
    $description = ucwords(htmlspecialchars(stripslashes($_POST['description'])));
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    
   
    $data = array(
        "description" => $description,
        "workers" => $workers,
        "updated_by" => $user,
        "updated_at" => $date
    );
 
    $update = new Update_table();
    $update->updateAny('tasks', $data, 'task_id', $task);
    if($update){
        
    ?>
    <div class="success"><p>Task Updated Successfully <i class="fas fa-thumbs-up"></i></p></div>
<?php } ?>
    
