<?php

    $group = ucwords(htmlspecialchars(stripslashes($_POST['account_group'])));
    $data = array(
        'account_group' => $group
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if department exists
    $check = new selects();
    $results = $check->fetch_count_cond('account_groups', 'account_group', $group);
    if($results > 0){
        echo "<p class='exist'>$group already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('account_groups', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$group added</p>";
        }
    }
    