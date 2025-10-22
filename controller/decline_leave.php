<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['id'])){
        $leave = $_GET['id'];
        $approved_by = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/inserts.php";
        include "../classes/select.php";
        
        $data = array(
            'leave_status' => -1,
            'approved_at' => $date,
            'approved_by' => $approved_by
        );
        //update staff status
        $recall = new Update_table;
        $recall->updateAny('leaves', $data, 'leaves_id', $leave);
        if($recall){
            echo "<div class='success'><p>Leave request Declined successfully! <i class='fas fa-thumbs-down'></i></p></div>";
        }
    }