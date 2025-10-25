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
        include "../classes/update.php";
        include "../classes/inserts.php";
        include "../classes/select.php";
        //get staff details
        $get_details = new selects();
        $stfs = $get_details->fetch_details_group('payroll', 'staff', 'payroll_id', $payroll);
        $staff = $stfs->staff;
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }

        $data = array(
            'approved' => $date,
            'approved_by' => $approved_by,
            'payroll_status' => 1
        );
        $approve = new Update_table();
        $approve->updateAny('payroll', $data, 'payroll_id', $payroll);

        if($approve){
            echo "<div class='success'><p>Pay slip approved for $full_name successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }