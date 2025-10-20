<?php

    $discipline = strtoupper(htmlspecialchars(stripslashes($_POST['discipline'])));
    $data = array(
        'discipline' => $discipline
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if menu exists
    $check = new selects();
    $results = $check->fetch_count_cond('disciplines', 'discipline', $discipline);
    if($results > 0){
        echo "<p class='exist'>$discipline already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('disciplines', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$discipline added</p>";
        }
    }
    