<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    if(isset($_GET['cycle_id'])){
        $id = htmlspecialchars(stripslashes($_GET['cycle_id']));
        //instantiate class
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        include "../classes/inserts.php";
        //get cycle details
        $get_cycle = new selects();
        $cycle = $get_cycle->fetch_details_cond('crop_cycles', 'cycle_id', $id);
        if(gettype($cycle) === 'array'){
            foreach($cycle as $cyc){
                $field_id = $cyc->field;
                $crop_id = $cyc->crop;
            }
            //get field name
            $field = $get_cycle->fetch_details_group('fields', 'field_name', 'field_id', $field_id);
            $field_name = $field->field_name;
            //get crop name
            $crop = $get_cycle->fetch_details_group('items', 'item_name', 'item_id', $crop_id);
            $crop_name = $crop->item_name;
        }
        //update cycle status
        $data = array(
            'cycle_status' => 1,
            'ended_by' => $user,
            'end_date' => $date
        );
        $update = new Update_table;
        $update->updateAny('crop_cycles', $data, 'cycle_id', $id);
        if($update){
            echo "<div class='success'><p>$crop_name Crop Cycle Completed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }   

    }
    