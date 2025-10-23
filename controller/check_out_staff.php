<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $attendance_id = htmlspecialchars(stripslashes($_POST['attendance_id']));
        $time = htmlspecialchars(stripslashes($_POST['attendance_time']));
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        
        $data = array(
            'time_out' => $time,
            'checked_out' => $date,
            'checked_out_by' => $user
        );
        $mark = new Update_table();
        $mark->updateAny('attendance', $data, 'attendance_id', $attendance_id);
        if($mark){
            echo "<div class='success'><p>$full_name checked out successfully. <i class='fas fa-thumbs-up'></i></p></div>";
        }
    
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }