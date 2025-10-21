<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $leave = htmlspecialchars(stripslashes($_POST['leave']));
        $start = htmlspecialchars(stripslashes($_POST['start_date']));
        $end = htmlspecialchars(stripslashes($_POST['end_date']));
        $max_days = htmlspecialchars(stripslashes($_POST['max_days']));
        $total_days = htmlspecialchars(stripslashes($_POST['total_days']));
        $reason = htmlspecialchars(stripslashes($_POST['reason']));
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        //get leave details
        $results = $get_details->fetch_details_cond('leave_types', 'leave_id', $leave);
        foreach($results as $result){
            $title = $result->leave_title;
        }
        //check if staff already have a leave application 
        $check = $get_details->fetch_count_2cond('leaves', 'employee', $staff, 'leave_status', 0);
        //check if staff have an ongoing leave or already approved
        $check2 = $get_details->fetch_count_2cond('leaves', 'employee', $staff, 'leave_status', 1);
        if($check > 0){
            echo "<script>alert('There is an existing leave application for $full_name! Kindly wait for approval')</script>";
            echo "<div class='success'><p style='background:brown'>There is an existing leave application for $full_name! Kindly wait for approval. <i class='fas fa-thumb-tack'></i></p></div>";
            exit();
        }elseif($check2 > 0){
            echo "<script>alert('$full_name already has an approved $title! Cannot proceed')</script>";
            echo "<div class='success'><p style='background:brown'>$full_name already has an approved $title! Cannot proceed. <i class='fas fa-thumbs-down'></i></p></div>";
            exit();
        }else{
            $data = array(
                'employee' => $staff,
                'leave_type' => $leave,
                'max_days' => $max_days,
                'start_date' => $start,
                'end_date' => $end,
                'total_days' => $total_days,
                'reason' => $reason,
                'applied' => $date,
                'posted_by' => $user,
                'store' => $store
            );
            $add_leave = new add_data('leaves', $data);
            $add_leave->create_data();
            if($add_leave){
                echo "<div class='success'><p>$full_name's application for a $title is successful. <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }