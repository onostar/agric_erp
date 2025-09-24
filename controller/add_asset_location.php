<?php

    $location = strtoupper(htmlspecialchars(stripslashes($_POST['location'])));
    $data = array(
        'location' => $location
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if department exists
    $check = new selects();
    $results = $check->fetch_count_cond('asset_locations', 'location', $location);
    if($results > 0){
        echo "<p class='exist'>$location already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('asset_locations', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$location added</p>";
        }
    }
    