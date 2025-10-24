<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    // $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $title = htmlspecialchars(stripslashes($_POST['title']));
    $min_income = htmlspecialchars(stripslashes($_POST['min_income']));
    $max_income = htmlspecialchars(stripslashes($_POST['max_income']));
    $rate = htmlspecialchars(stripslashes($_POST['tax_rate']));
    $data = array(
        'title' => $title,
        'min_income' => $min_income,
        'max_income' => $max_income,
        'tax_rate' => $rate,
        'created_by' => $user,
        'created_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check = new selects();
    //check if title exist
    $check1 = $check->fetch_count_cond('tax_rules', 'title', $title);
    //check if minimum income exist
    $check2 = $check->fetch_count_cond('tax_rules', 'min_income', $min_income);
    //check if maximum income exist
    $check3 = $check->fetch_count_cond('tax_rules', 'max_income', $max_income);

    if($check1 > 0){
        echo "<p class='exist'><span>$title</span> already exists</p>";
        exit;
    }elseif($check2 > 0){
        echo "<p class='exist'><span>$min_income</span> already exists in tax rules</p>";
        exit;
    }elseif($check3 > 0){
        echo "<p class='exist'><span>$max_income</span> already exists in tax rules</p>";
    }else{
        //create item
        $add_data = new add_data('tax_rules', $data);
        $add_data->create_data();
        //update field status to occupied
        
        if($add_data){
            echo "<div class='success'><p>$title added to tax rules successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to add tax rules <i class='fas fa-thumbs-down'></i></p>";
        }
    }