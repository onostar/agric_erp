<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['id'])){
        $staff = $_GET['id'];
        $resigned_by = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/inserts.php";
        include "../classes/select.php";
        //get staff details
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        $data = array(
            'staff_status' => 2,
            'resigned' => $date,
            'resigned_by' => $resigned_by
        );
        //update staff status
        $recall = new Update_table;
        $recall->updateAny('staffs', $data, 'staff_id', $staff);
        if($recall){
            echo "<div class='success'><p>You have successfully retired $full_name from service! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }