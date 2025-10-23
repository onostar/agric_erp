<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $time = htmlspecialchars(stripslashes($_POST['attendance_time']));
        $remark = htmlspecialchars(stripslashes($_POST['remark']));
        $currentDay = date("Y-m-d");
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        
        //check if staff already checks in
        $check = $get_details->fetch_count_curDatePosCon('attendance', 'attendance_date', 'staff', $staff);
        if($check > 0){
            echo "<script>alert('$full_name! already checked in today')</script>";
            echo "<div class='success'><p style='background:brown'>$full_name! already checked in today. <i class='fas fa-thumb-tack'></i></p></div>";
            exit();
        }else{
            $data = array(
                'staff' => $staff,
                'attendance_date' => $currentDay,
                'time_in' => $time,
                'remark' => $remark,
                'marked_date' => $date,
                'marked_by' => $user,
                'store' => $store
            );
            $mark= new add_data('attendance', $data);
            $mark->create_data();
            if($mark){
                echo "<div class='success'><p>Attendance marked for $full_name successfully. <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }