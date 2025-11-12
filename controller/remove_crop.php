<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $trans_type ="crop removal";

    // $crop = htmlspecialchars(stripslashes($_POST['crop']));
    $crop =  1; //pineapple
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $reason = ucwords(htmlspecialchars(stripslashes($_POST['reason'])));
    $notes = ucwords(htmlspecialchars(stripslashes($_POST['other_notes'])));

    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get item details
    $get_details = new selects;
    //get cycle details
    $cycs = $get_details->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
    foreach($cycs as $cyc){
        $field = $cyc->field;
        $yield = $cyc->expected_yield;
    }
    //get field details
    $fids = $get_details->fetch_details_cond('fields', 'field_id', $field);
    foreach($fids as $fid){
        $field_name = $fid->field_name;
    }
    //generate transaction number
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    $trx_num = "TR".$ran_num.$todays_date;
    
    $details = "Removed $quantity of Pineapples from $field_name field";
   
    //data to stockin into removals table
    $remove_data = array(
        'cycle' => $cycle,
        'crop' => $crop,
        'farm' => $farm,
        'field' => $field,
        'quantity' => $quantity,
        'reason' => $reason,
        'other_notes' => $notes,
        'trx_number' => $trx_num,
        'removed_by' => $user,
        'date_removed' => $date
    );
    $stock_in = new add_data('crop_removal', $remove_data);
    $stock_in->create_data();
    if($stock_in){

        echo "<div class='success'><p>$quantity Pineapples Removed from $field_name successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to remove crop <i class='fas fa-thumbs-down'></i></p>";
    }