<?php

    $group = htmlspecialchars(stripslashes($_POST['group']));
    $sub_group = ucwords(htmlspecialchars(stripslashes($_POST['sub_group'])));
    $data = array(
        'account_group' => $group,
        'sub_group' => $sub_group
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if department exists
    $check = new selects();
    $results = $check->fetch_count_2cond('account_sub_groups', 'account_group', $group, 'sub_group', $sub_group);
    if($results > 0){
        echo "<p class='exist'>$sub_group already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('account_sub_groups', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$sub_group added</p>";
        }
    }
    