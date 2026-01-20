<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['id'])){
        $staff = $_GET['id'];
        $suspened_by = $_SESSION['user_id'];
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

        $data = array(
            'staff' => $staff,
            'suspension_date' => $date,
            'suspended_by' => $suspened_by,
            'store' => $store
        );
        $suspend = new add_data('suspensions', $data);
        $suspend->create_data();

        $disable_user = new Update_table();
        $disable_user->update('staffs', 'staff_status', 'staff_id', 1, $staff);
        //disable staff login
        $disable_user->update('users', 'status', 'staff_id', -1, $staff);
        if($disable_user){
            echo "<div class='success'><p>You have successfully suspended $full_name! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }