<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['payroll'])){
        $payroll = htmlspecialchars(stripslashes($_GET['payroll']));
        $approved_by = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
        $store = $_SESSION['store_id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";
        //get staff details
        $get_details = new selects();
        $stfs = $get_details->fetch_details_group('payroll', 'staff', 'payroll_id', $payroll);
        $staff = $stfs->staff;
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }

       $delete = new deletes();
       $delete->delete_item('payroll', 'payroll_id', $payroll);

        if($delete){
            echo "<div class='success'><p style='background:brown'>Pay slip declined for $full_name! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }