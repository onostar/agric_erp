<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $staff = htmlspecialchars(stripslashes($_POST['staff']));
    $beneficiary = strtoupper(htmlspecialchars(stripslashes($_POST['beneficiary'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes($_POST['address'])));
    $entitlement = htmlspecialchars(stripslashes($_POST['entitlement']));
    $relation = strtoupper(htmlspecialchars(stripslashes($_POST['relationship'])));
    $gender = htmlspecialchars(stripslashes($_POST['gender']));
    $date = date("Y-m-d H:i:s");
    $data = array(
        'staff' => $staff,
        'beneficiary' => $beneficiary,
        'phone' => $phone,
        'address' => $address,
        'entitlement' => $entitlement,
        'relation' => $relation,
        'gender' => $gender,
        'date_added' => $date
    );
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    //check if beneficiary already exists
    $check = new selects();
    $results = $check->fetch_count_2cond('beneficiaries', 'beneficiary', $beneficiary, 'staff', $staff);
    if($results > 0){
        echo "<p class='exist'>$beneficiary already exists for this staff</p>";
    }else{
        //add beneficiary
        $add_data = new add_data('beneficiaries', $data);
        $add_data->create_data();
        if($add_data){
            echo "<p>$beneficiary added successfully</p>";
        }
    }