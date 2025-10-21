<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['id'])){
        $leave = $_GET['id'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";
        //get leave details
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('leave_types', 'leave_id', $leave);
        foreach($rows as $row){
            $title = $row->leave_title;
        }
        $disable = new Update_table();
        $disable->update('leave_types', 'leave_status', 'leave_id', 0, $leave);
        if($disable){
            echo "<div class='success'><p>You have successfully activated $title! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }