<?php
    date_default_timezone_set("Africa/Lagos");
    $fullname = ucwords(htmlspecialchars(stripslashes($_POST['full_name'])));
    $staff_id = ucwords(htmlspecialchars(stripslashes($_POST['staff_id'])));
    $username = ucwords(htmlspecialchars(stripslashes($_POST['username'])));
    $role = ucwords(htmlspecialchars(stripslashes($_POST['user_role'])));
    $store = htmlspecialchars(stripslashes($_POST['store_id']));
    $password = 123;
    $date = date("Y-m-d H:i:s");
    $data = array(
        'full_name' => $fullname,
        'username' => $username,
        'user_role' => $role,
        'store' => $store,
        'user_password' => $password,
        'reg_date' => $date
    );
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";

    //check if staff already has an account
    $check = new selects();
    $results = $check->fetch_count_cond1neg('staffs', 'staff_id', $staff_id, 'user_id', 0);
    if($results > 0){
        echo "<p class='exist'>$fullname already has an account</p>";
    }else{
        //check if user exists
        $results = $check->fetch_count_cond('users', 'username', $username);
        if($results > 0){
            echo "<p class='exist'>$username already exists</p>";
        }else{
            //create user
            $add_data = new add_data('users', $data);
            $add_data->create_data();
            if($add_data){
                //get id
                $get_last = new selects();
                $ids = $get_last->fetch_lastInserted('users', 'user_id');
                $user_id = $ids->user_id;
                //update staff status
                $data2 = array(
                    // 'staff_status' => 1,
                    'user_id' => $user_id
                );
                $update = new Update_table();
                $update->updateAny('staffs', $data2, 'staff_id', $staff_id);
                echo "<p>$fullname Created</p>";
            }
        }
    }