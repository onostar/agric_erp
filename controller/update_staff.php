<?php
    date_default_timezone_set("Africa/Lagos");

    session_start();
    $user = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];
    $last_name = strtoupper(htmlspecialchars(stripslashes($_POST['last_name'])));
    $other_names = strtoupper(htmlspecialchars(stripslashes($_POST['other_names'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes($_POST['address'])));
    $email = htmlspecialchars(stripslashes($_POST['email']));
    // $store = htmlspecialchars(stripslashes(($_POST['customer_store'])));
    $dob = htmlspecialchars(stripslashes($_POST['dob']));
    $staff_id = htmlspecialchars(stripslashes(($_POST['staff_id'])));
    $staff_num = htmlspecialchars(stripslashes(($_POST['staff_num'])));
    $title = htmlspecialchars(stripslashes($_POST['title']));
    $gender = htmlspecialchars(stripslashes($_POST['gender']));
    $marital_status = htmlspecialchars(stripslashes($_POST['marital_status']));
    $religion = htmlspecialchars(stripslashes(($_POST['religion'])));
    $department = htmlspecialchars(stripslashes($_POST['department']));
    $nok = strtoupper(htmlspecialchars(stripslashes($_POST['nok'])));
    $discipline = ucwords(htmlspecialchars(stripslashes($_POST['discipline'])));
    $nok_phone = htmlspecialchars(stripslashes($_POST['nok_phone']));
    $relation = strtoupper(htmlspecialchars(stripslashes($_POST['nok_relation'])));
    $category = htmlspecialchars(stripslashes($_POST['staff_category']));
    $group = htmlspecialchars(stripslashes($_POST['staff_group']));
    $designation = htmlspecialchars(stripslashes($_POST['designation']));
    $bank = htmlspecialchars(stripslashes($_POST['bank']));
    $account = htmlspecialchars(stripslashes($_POST['account_num']));
    $pension = htmlspecialchars(stripslashes($_POST['pension']));
    $pension_num = htmlspecialchars(stripslashes($_POST['pension_num']));
    $employed = htmlspecialchars(stripslashes($_POST['employed']));
    $date = date("Y-m-d H:i:s");
    $todays_date = date("dmyh");
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";

    $data = array(
        'last_name' => $last_name,
        'other_names' => $other_names,
        'phone' => $phone,
        'email_address' => $email,
        'home_address' => $address,
        'gender' => $gender,
        'dob' => $dob,
        'employed' => $employed,
        'staff_number' => $staff_num,
        'title' => $title,
        'discipline' => $discipline,
        'religion' => $religion,
        'marital_status' => $marital_status,
        'nok' => $nok,
        'staff_group' => $group,
        'nok_phone' => $nok_phone,
        'nok_relation' => $relation,
        'staff_category' => $category,
        'designation' => $designation,
        'department' => $department,
        'bank' => $bank,
        'account_num' => $account,
        'pension_num' => $pension_num,
        'pension' => $pension,
    );
    // $where = "staff_id = $staff_id";
       $update = new Update_table();
       $update->updateAny('staffs', $data, 'staff_id', $staff_id);
       if($update){
        echo "<div class='success'><p><span>$last_name $other_names</span> updated successfully!</p></div>";
       }else{
        echo "<div class='error'><p>Update Failed!</p></div>";
       }
       
   

   ?>