<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $title = strtoupper(htmlspecialchars(stripslashes($_POST['title'])));
    $max_days = htmlspecialchars(stripslashes($_POST['max_days']));
    $description = htmlspecialchars(stripslashes($_POST['description']));
    $data = array(
        'leave_title' => $title,
        'max_days' => $max_days,
        'description' => $description
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check = new selects();
    //check if leave type already Exist
    $results = $check->fetch_count_cond('leave_types', 'leave_title', $title);
    if($results > 0){
        echo "<p class='exist'>$title already exists</p>";
    }else{
        //create item
        $add_data = new add_data('leave_types', $data);
        $add_data->create_data();
        if($add_data){
            echo "<div class='success'><p>$title created successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to start create leave type <i class='fas fa-thumbs-down'></i></p>";
        }
    }