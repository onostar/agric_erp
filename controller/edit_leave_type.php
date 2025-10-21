<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $leave = htmlspecialchars(stripslashes($_POST['leave']));
    $title = htmlspecialchars(stripslashes($_POST['title']));
    $max_days = htmlspecialchars(stripslashes($_POST['max_days']));
    $description = htmlspecialchars(stripslashes($_POST['description']));
   
    $data = array(
        'leave_title' => $title,
        'max_days' => $max_days,
        'description' => $description
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";
    $update_data = new update_table;
    $update_data->updateAny('leave_types', $data, 'leave_id', $leave);
    
    if($update_data){
        echo "<div class='success'><p>$title Updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to update leave type <i class='fas fa-thumbs-down'></i></p>";
    }
    