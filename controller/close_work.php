<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $time = date("H:i:s");
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        //get attendance id for the staff for today
        $attendance_rows = $get_details->fetch_details_2cond('attendance', 'staff', 'attendance_date', $staff,  date("Y-m-d"));
        foreach($attendance_rows as $attendance_row){
            $attendance_id = $attendance_row->attendance_id;
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
            session_destroy();
            session_unset();
        }
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }