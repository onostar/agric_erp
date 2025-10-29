<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $field = strtoupper(htmlspecialchars(stripslashes($_POST['field'])));
    $size = htmlspecialchars(stripslashes($_POST['field_size']));
    $soil_type = htmlspecialchars(stripslashes($_POST['soil_type']));
    $ph = htmlspecialchars(stripslashes($_POST['soil_ph']));
    $amount = htmlspecialchars(stripslashes($_POST['rent']));
    $latitude = htmlspecialchars(stripslashes($_POST['latitude']));
    $longitude = htmlspecialchars(stripslashes($_POST['longitude']));
    $location = htmlspecialchars(stripslashes($_POST['location']));
    $topography = ucwords(htmlspecialchars(stripslashes($_POST['topography'])));
    $data = array(
        'field_name' => $field,
        'farm' => $farm,
        'field_size' => $size,
        'soil_type' => $soil_type,
        'soil_ph' => $ph,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'location' => $location,
        'rent' => $amount,
        'topography' => $topography,
        'created_by' => $user,
        'created_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if item already Exist
    $check = new selects();
    $results = $check->fetch_count_2cond('fields', 'farm', $farm, 'field_name', $field);
    if($results > 0){
        echo "<p class='exist'><span>$field</span> already exists</p>";
    }else{
        //create item
        $add_data = new add_data('fields', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p><span>$field</span> created successfully!</p>";
        }
    }