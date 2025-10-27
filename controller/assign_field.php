<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $id = strtoupper(htmlspecialchars(stripslashes($_POST['field_id'])));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $assign_data = array(
        'field' => $id,
        'customer' => $customer,
        'assigned_by' => $user,
        'assigned_date' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    include "../classes/inserts.php";
    //update field
    $update = new Update_table;
    $update->update('fields', 'customer', 'field_id', $customer, $id);
    if($update){
        //check if customer is in field before
        //insert into field assignment table
        
        $add_data = new add_data('assigned_fields', $assign_data);
        $add_data->create_data();
        echo "<div class='success'><p>Field assigned to client successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
    
