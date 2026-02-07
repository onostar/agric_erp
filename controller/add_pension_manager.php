<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $pension = strtoupper(htmlspecialchars(stripslashes($_POST['pension'])));
    $data = array(
        'pension_manager' => $pension,
        'added_by' => $user,
        'post_date' => $date
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if menu exists
    $check = new selects();
    $results = $check->fetch_count_cond('pension_managers', 'pension_manager', $pension);
    if($results > 0){
        echo "<p class='exist'>$pension already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('pension_managers', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$pension added</p>";
        }
    }
    