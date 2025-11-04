<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $field = htmlspecialchars(stripslashes($_POST['field']));
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    // $crop = htmlspecialchars(stripslashes($_POST['crop']));
    $area = htmlspecialchars(stripslashes($_POST['area_used']));
    // $variety = htmlspecialchars(stripslashes($_POST['variety']));
    $start_date = htmlspecialchars(stripslashes($_POST['start_date']));
    $harvest = htmlspecialchars(stripslashes($_POST['harvest']));
    $note = ucwords(htmlspecialchars(stripslashes($_POST['notes'])));
    $yield = htmlspecialchars(stripslashes($_POST['yield']));
    $data = array(
        'field' => $field,
        /* 'crop' => $crop,
        'variety' => $variety, */
        'area_used' => $area,
        'start_date' => $start_date,
        'expected_harvest' => $harvest,
        'expected_yield' => $yield,
        'notes' => $note,
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get previous cycle details
    $check = new selects();
    $rows = $check->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
    foreach($rows as $row){
        $old_field = $row->field;
        // $old_crop = $row->crop;
        $old_area = $row->area_used;
        // $old_variety = $row->variety;
        $old_start = $row->start_date;
        $old_harvest = $row->expected_harvest;
        $old_yield = $row->expected_yield;
        $old_note = $row->notes;
    }
    
    //check if field was changed and update status of old and new field
    if($old_field != $field){
        //update old field status to available
        $update_field = new update_table;
        $update_field->update('fields', 'field_status', 'field_id', 0, $old_field);
        //check new field size
        $fields = $check->fetch_details_cond('fields', 'field_id', $field);
        if(is_array($fields)){
            foreach($fields as $field_row){
                $field_size = $field_row->field_size;
            }
        }
        //get total area used in field
        $areas = $check->fetch_sum_double('crop_cycles', 'area_used', 'field', $field, 'cycle_status', 0);
        if(is_array($areas)){
            foreach($areas as $area_row){
                $total_used = $area_row->total;
            }
        }else{
            $total_used = 0;
        }
        if(($total_used + $area) > $field_size){
            echo "<p class='exist'>The area used ($area Hec) exceeds the available field size. Available size is ".($field_size - $total_used)." Hec</p>";
            echo "<script>The area used ($area Hec) exceeds the available field size. Available size is ".($field_size - $total_used)." Hec</script>";
            exit();
        }elseif(($total_used + $area) == $field_size){
            //update new field status to occupied
            $update_new_field = new update_table;
            $update_new_field->update('fields', 'field_status', 'field_id', 1, $field);
        }
    }else{
        //check size
        $fields = $check->fetch_details_cond('fields', 'field_id', $field);
        if(is_array($fields)){
            foreach($fields as $field_row){
                $field_size = $field_row->field_size;
            }
        }
        //get total area used in field excluding current cycle
        $areas = $check->fetch_sum_tripple1Neg('crop_cycles', 'area_used', 'field', $field, 'cycle_status', 0, 'cycle_id', $cycle);
        if(is_array($areas)){
            foreach($areas as $area_row){
                $total_used = $area_row->total;
            }
        }else{
            $total_used = 0;
        }
        if(($total_used + $area) > $field_size){
            echo "<p class='exist'>The area used ($area Hec) exceeds the available field size. Available size is ".($field_size - $total_used)." Hec</p>";
            echo "<script>The area used ($area Hec) exceeds the available field size. Available size is ".($field_size - $total_used)." Hec</script>";
            exit();
        }elseif(($total_used + $area) == $field_size){
            //update new field status to occupied
            $update_new_field = new update_table;
            $update_new_field->update('fields', 'field_status', 'field_id', 1, $field);
        }
    }
    $update_data = new update_table;
    $update_data->updateAny('crop_cycles', $data, 'cycle_id', $cycle);
    //get field name
    $fields = $check->fetch_details_group('fields', 'field_name', 'field_id', $field);
    $field_name = $fields->field_name;
    //get crop name
    /* $crops = $check->fetch_details_group('items', 'item_name', 'item_id', $crop);
    $crop_name = $crops->item_name; */
    //add to activity log
    $activity = array(
        'cycle' => $cycle,
        'old_field' => $old_field,
        'new_field' => $field,
        /* 'old_crop' => $old_crop,
        'new_crop' => $crop, */
        'old_area' => $old_area,
        'new_area' => $area,
        /* 'old_variety' => $old_variety,
        'new_variety' => $variety, */
        'old_start' => $old_start,
        'new_start' => $start_date,
        'old_harvest' => $old_harvest,
        'new_harvest' => $harvest,
        'old_yield' => $old_yield,
        'new_yield' => $yield,
        'old_notes' => $old_note,
        'new_notes' => $note,
        'updated_by' => $user,
        'updated_at' => $date
    );
    $add_data = new add_data('cycle_changes', $activity);
    $add_data->create_data();
    if($add_data){
        echo "<div class='success'><p>Crop Cycle Updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }else{
        echo "<p style='background:red; color:#fff; padding:5px'>Failed to update crop cycle <i class='fas fa-thumbs-down'></i></p>";
    }
    