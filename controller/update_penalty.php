<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    // $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $penalty_id = htmlspecialchars(stripslashes($_POST['penalty_id']));
    $penalty = strtoupper(htmlspecialchars(stripslashes($_POST['penalty'])));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $data = array(
        'penalty' => $penalty,
        'amount' => $amount,
        'updated_by' => $user,
        'updated_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    $check = new selects();
    //check if penalty exist
    $check1 = $check->fetch_count_cond1neg('penalty_fees', 'penalty', $penalty, 'penalty_id', $penalty_id);
   
    if($check1 > 0){
        echo "<p class='exist'><span>$penalty</span> already exists</p>";
        exit;
    }else{
        //create item
        $update = new Update_table();
        $update->updateAny('penalty_fees', $data, 'penalty_id', $penalty_id);
        //update field status to occupied
        
        if($update){
            echo "<div class='success'><p>$penalty updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to update penalty <i class='fas fa-thumbs-down'></i></p>";
        }
    }