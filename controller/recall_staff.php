<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['id'])){
        $staff = $_GET['id'];
        $recalled_by = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
        $store = $_SESSION['store_id'];
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
        //check if staff is in suspension table and get details
        $sus = $get_details->fetch_details_2cond('suspensions', 'staff', 'suspension_status', $staff, 0);
        foreach($sus as $su){
            $suspesion_id = $su->suspension_id;
        }

        $data = array(
            'suspension_status' => 1,
            'recall_date' => $date,
            'recalled_by' => $recalled_by
        );
        //update on suspensin table
        $recall = new Update_table;
        $recall->updateAny('suspensions', $data, 'suspension_id', $suspesion_id);
        //update staff status on staff table
        $recall->update('staffs', 'staff_status', 'staff_id', 0, $staff);
        if($recall){
            echo "<div class='success'><p>You have successfully recalled $full_name to service! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }