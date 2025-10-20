<?php

    $designation = strtoupper(htmlspecialchars(stripslashes($_POST['designation'])));
    $data = array(
        'designation' => $designation
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if menu exists
    $check = new selects();
    $results = $check->fetch_count_cond('designations', 'designation', $designation);
    if($results > 0){
        echo "<p class='exist'>$designation already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('designations', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$designation added</p>";
        }
    }
    