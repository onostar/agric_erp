<?php
date_default_timezone_set("Africa/Lagos");
    session_start();
    $user = $_SESSION['user_id'];
    $asset = strtoupper(htmlspecialchars(stripslashes($_POST['asset'])));
    $asset_id = strtoupper(htmlspecialchars(stripslashes($_POST['asset_id'])));
    $supplier = strtoupper(htmlspecialchars(stripslashes($_POST['supplier'])));
    $purchase = htmlspecialchars(stripslashes($_POST['purchase_date']));
    $cost = htmlspecialchars(stripslashes($_POST['cost']));
    $salvage = htmlspecialchars(stripslashes($_POST['salvage_value']));
    $useful_life = htmlspecialchars(stripslashes($_POST['useful_life']));
    $deploy = htmlspecialchars(stripslashes($_POST['deployment']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $location = htmlspecialchars(stripslashes($_POST['location']));
    $ledger = htmlspecialchars(stripslashes($_POST['ledger']));
    $spec = ucwords(htmlspecialchars(stripslashes($_POST['specification'])));
    $date = date("Y-m-d H:i:s");
    $asset_year = date("Y", strtotime($purchase));
    $asset_month = date("m", strtotime($purchase));
    
    
    //instantiate class
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get location
    $get_details = new selects();
    $cat_names = $get_details->fetch_details_cond('stores',  'store_id', $location);
    foreach($cat_names as $cat_name){
        $locate =  $cat_name->store;
    }
    $loc = substr($locate, 0, 3);
    $asset_no = "DAVIDORLAH/".$loc."/".$asset_year."/".$asset_month."/".$asset_id;

    //get ledger details
    $ledgs = $get_details->fetch_details_cond('ledgers', 'acn', $ledger);
    foreach($ledgs as $ledg){
        $ledger_name = $ledg->ledger;
    }
    $data = array(
        'asset' => $asset,
        'asset_no' => $asset_no,
        'location' => $location,
        'supplier' => $supplier,
        'cost' => $cost,
        'quantity' => $quantity,
        'book_value' => $cost,
        'useful_life' => $useful_life,
        'salvage_value' => $salvage,
        'ledger' => $ledger,
        'specification' => $spec,
        'deployment_date' => $deploy,
        'purchase_date' => $purchase,
        'updated_at' => $date,
        'updated_by' => $user,
    );

    //check if asset exists
    $results = $get_details->fetch_count_2cond1neg('assets', 'asset', $asset,'location', $location,'asset_id', $asset_id);
    if($results > 0){
        echo "<p class='exist'>$asset already exists for $locate</p>";
    }else{
        //add new record
        $add_data = new Update_table();
        $add_data->updateAny('assets', $data, 'asset_id', $asset_id);
        if($add_data){
            //check if asset is a land asset and also add to field table.
            //check if asset exists in field table before
            $field_exists = $get_details->fetch_count_cond('fields', 'asset_id', $asset_id);
            if($field_exists > 0){
                //update purchase cost
                $update_field = new Update_table();
                $update_field->update_double('fields', 'purchase_cost', $cost, 'field_name',$asset, 'asset_id', $asset_id);
            }else{
                //add new record
                if($ledger_name == "LAND AND BUILDING"){
                    $field_data = array(
                        'asset_id' => $asset_id,
                        'field_name' => $asset,
                        'purchase_cost' => $cost,
                        'created_by' => $user,
                        'created_at' => $date
                    );
                    $add_field = new add_data('fields', $field_data);
                    $add_field->create_data();
                }
            }

            echo "<div class='success'><p>$asset updated successfully <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }
    