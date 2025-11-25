<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    // $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    
    $rate = htmlspecialchars(stripslashes($_POST['rate']));
    $data = array(
        'rate' => $rate,
        'added_by' => $user,
        'added_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check = new selects();
   
    //create item
    $add_data = new add_data('exchange_rates', $data);
    $add_data->create_data();
    
    if($add_data){
        echo "<div class='success'><p>Exchange rate added  successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to add exchange rate <i class='fas fa-thumbs-down'></i></p>";
    }
    