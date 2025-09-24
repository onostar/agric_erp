<?php

    $group = htmlspecialchars(stripslashes($_POST['group']));
    $sub_group = htmlspecialchars(stripslashes($_POST['sub_group']));
    $class = ucwords(htmlspecialchars(stripslashes($_POST['account_class'])));
    $data = array(
        'account_group' => $group,
        'sub_group' => $sub_group,
        'class' => $class,
    );
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if class exists
    $check = new selects();
    $results = $check->fetch_count_2cond('account_class', 'sub_group', $sub_group, 'class', $class);
    if($results > 0){
        echo "<p class='exist'>$class already exists</p>";
    }else{
        //add new record
        $add_data = new add_data('account_class', $data);
        $add_data->create_data();
        
        if($add_data){
            
            echo "<p>$class added</p>";
        }
    }
    