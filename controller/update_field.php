<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $id = strtoupper(htmlspecialchars(stripslashes($_POST['field_id'])));
    $field = strtoupper(htmlspecialchars(stripslashes($_POST['field'])));
    $size = htmlspecialchars(stripslashes($_POST['field_size']));
    $soil_type = htmlspecialchars(stripslashes($_POST['soil_type']));
    $ph = htmlspecialchars(stripslashes($_POST['soil_ph']));
    $topography = ucwords(htmlspecialchars(stripslashes($_POST['topography'])));
    // $customer = htmlspecialchars(stripslashes($_POST['customer']));
     $amount = htmlspecialchars(stripslashes($_POST['rent']));
    $latitude = htmlspecialchars(stripslashes($_POST['latitude']));
    $longitude = htmlspecialchars(stripslashes($_POST['longitude']));
    $location = htmlspecialchars(stripslashes($_POST['location']));
    
    $data = array(
        'field_name' => $field,
        'field_size' => $size,
        'soil_type' => $soil_type,
        'soil_ph' => $ph,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'rent' => $amount,
        'topography' => $topography,
        'location' => $location,
        'updated_by' => $user,
        'updated_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    include "../classes/inserts.php";
    //update field
    $update = new Update_table;
    $update->updateAny('fields', $data, 'field_id', $id);
    if($update){
        echo "<div class='success'><p>Field Details updated for $field successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
    
