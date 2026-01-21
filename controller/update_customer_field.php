<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $id = strtoupper(htmlspecialchars(stripslashes($_POST['assigned_id'])));
   
    $size = htmlspecialchars(stripslashes($_POST['field_size']));
   
    $latitude = htmlspecialchars(stripslashes($_POST['latitude']));
    $longitude = htmlspecialchars(stripslashes($_POST['longitude']));
   
    $data = array(
        'field_size' => $size,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'updated_by' => $user,
        'updated_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    include "../classes/inserts.php";
    //get field details
    $check = new selects();
    
    //update field
    $update = new Update_table;
    $update->updateAny('assigned_fields', $data, 'assigned_id', $id);
    if($update){
        
        echo "<div class='success'><p>Land Details updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
    
