<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $attendance = htmlspecialchars(stripslashes($_POST['attendance']));
    $date = date("Y-m-d H:i:s");
    $store = $_SESSION['store_id'];

    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/delete.php";
    $get_details = new selects();
    //get attendance details
    $rows = $get_details->fetch_details_cond('attendance', 'attendance_id', $attendance);
    foreach($rows as $row){
        $staff = $row->staff;
        $attendance_date = $row->attendance_date;
        $time_in = $row->time_in;
        $latitude = $row->latitude;
        $longitude = $row->longitude;
        $ip_address = $row->ip_address;
        $location = $row->location;
    }
    //get staff full name
    $names = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
    foreach($names as $name){
        $full_name = $name->last_name." ".$name->other_names;
    }
    //delete from attendance
    $delete = new deletes();
    $delete->delete_item('attendance', 'attendance_id', $attendance);

    //add to attendnace flagging table
    $data = array(
        'staff' => $staff,
        'attendance_date' => $attendance_date,
        'time_in' => $time_in,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'ip_address' => $ip_address,
        'location' => $location,
        'post_date' => $date,
        'store' => $store,
        'flagged_by'=> $user
    );
    $add_data = new add_data('attendance_flagging', $data);
    $add_data->create_data();

    echo "<div class='success'><p>$full_name Attendance flagged and removed from today's attendance register. <i class='fas fa-thumbs-up'></i></p></div>";

