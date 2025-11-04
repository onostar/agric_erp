<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $field = htmlspecialchars(stripslashes($_POST['field']));
    // $crop = htmlspecialchars(stripslashes($_POST['crop']));
    $area = htmlspecialchars(stripslashes($_POST['area_used']));
    // $variety = htmlspecialchars(stripslashes($_POST['variety']));
    $start_date = htmlspecialchars(stripslashes($_POST['start_date']));
    $harvest = htmlspecialchars(stripslashes($_POST['harvest']));
    $note = ucwords(htmlspecialchars(stripslashes($_POST['notes'])));
    // $yield = htmlspecialchars(stripslashes($_POST['yield']));
    $data = array(
        'farm' => $farm,
        'field' => $field,
        /* 'crop' => $crop,
        'variety' => $variety, */
        'area_used' => $area,
        'start_date' => $start_date,
        'expected_harvest' => $harvest,
        // 'expected_yield' => $yield,
        'notes' => $note,
        'created_by' => $user,
        'created_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    $check = new selects();
    //get field name
    $fields = $check->fetch_details_cond('fields', 'field_id', $field);
    if(is_array($fields)){
        foreach($fields as $field_row){
            $field_name = $field_row->field_name;
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
    }else{
        //proceed
    //get crop name
    /* $crops = $check->fetch_details_group('items', 'item_name', 'item_id', $crop);
    $crop_name = $crops->item_name; */
    //check if item already Exist
    // $results = $check->fetch_count_3cond('crop_cycles', 'field', $field, 'crop', $crop, 'cycle_status', 0);
    //check if field is available

    /* if($results > 0){
        echo "<p class='exist'>There is an active crop cycle for <span>$crop_name</span> on $field_name already</p>";
    }else{ */
        //create item
        $add_data = new add_data('crop_cycles', $data);
        $add_data->create_data();
        //update field status to occupied if area is fuly used
        if(($total_used + $area) == $field_size){
            $update_field = new update_table;
            $update_field->update('fields', 'field_status', 'field_id', 1, $field);
        }
        
        if($add_data){
            echo "<div class='success'><p>New Crop Cycle started successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Failed to start crop cycle <i class='fas fa-thumbs-down'></i></p>";
        }
    }