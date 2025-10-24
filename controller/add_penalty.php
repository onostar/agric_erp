<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    // $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $penalty = strtoupper(htmlspecialchars(stripslashes($_POST['penalty'])));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $data = array(
        'penalty' => $penalty,
        'amount' => $amount,
        'created_by' => $user,
        'created_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check = new selects();
    //check if penalty exist
    $check1 = $check->fetch_count_cond('penalty_fees', 'penalty', $penalty);
   
    if($check1 > 0){
        echo "<p class='exist'><span>$penalty</span> already exists</p>";
        exit;
    }else{
        //create item
        $add_data = new add_data('penalty_fees', $data);
        $add_data->create_data();
        //update field status to occupied
        
        if($add_data){
            echo "<div class='success'><p>$penalty created successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to add penalty <i class='fas fa-thumbs-down'></i></p>";
        }
    }