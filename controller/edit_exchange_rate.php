<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    // $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    
    $rate = htmlspecialchars(stripslashes($_POST['rate']));
    $exchange = htmlspecialchars(stripslashes($_POST['exchange']));
    $data = array(
        'rate' => $rate,
        'updated_by' => $user,
        'updated_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";
   
    //create item
    $add_data = new Update_table();
    $add_data->updateAny('exchange_rates', $data, 'exchange_id', $exchange);
    
    if($add_data){
        echo "<div class='success'><p>Exchange rate updated  successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to update exchange rate <i class='fas fa-thumbs-down'></i></p>";
    }
    