<?php
date_default_timezone_set("Africa/Lagos");
    session_start();
    $store = $_SESSION['store_id'];
    $user = $_SESSION['user_id'];
    $asset = strtoupper(htmlspecialchars(stripslashes($_POST['asset'])));
    $supplier = strtoupper(htmlspecialchars(stripslashes($_POST['supplier'])));
    $purchase = htmlspecialchars(stripslashes($_POST['purchase_date']));
    $cost = htmlspecialchars(stripslashes($_POST['cost']));
    $salvage = htmlspecialchars(stripslashes($_POST['salvage_value']));
    $useful_life = htmlspecialchars(stripslashes($_POST['useful_life']));
    $deploy = htmlspecialchars(stripslashes($_POST['deployment']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $size = htmlspecialchars(stripslashes($_POST['size']));
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
    $asset_no = "DAVIDORLAH/".$loc."/".$asset_year."/".$asset_month."/";

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
        'size' => $size,
        'book_value' => $cost,
        'useful_life' => $useful_life,
        'salvage_value' => $salvage,
        'ledger' => $ledger,
        'specification' => $spec,
        'deployment_date' => $deploy,
        'purchase_date' => $purchase,
        'post_date' => $date,
        'posted_by' => $user,
    );

    //check if asset exists
    $results = $get_details->fetch_count_2cond('assets', 'asset', $asset,'location', $location);
    if($results > 0){
        echo "<p class='exist'>$asset already exists for $locate</p>";
    }else{
        //add new record
        $add_data = new add_data('assets', $data);
        $add_data->create_data();
        if($add_data){
            //update asset number
            //fetch last inserted
            $fetch_last = new selects();
            $ids = $fetch_last->fetch_lastInserted('assets', 'asset_id');
            $asset_id = $ids->asset_id;
            //fetch asset account
            $fetch_assets = new selects();
            $count = $fetch_assets->fetch_count('assets');
            $new_asset_no = $asset_no.$asset_id;
            $update_asset = new Update_table();
            $update_asset->update('assets', 'asset_no', 'asset_id', $new_asset_no, $asset_id);
            
            //check if asset is a land asset and also add to field table.
            if($ledger_name == "LAND AND BUILDING"){
                $field_data = array(
                    'asset_id' => $asset_id,
                    'field_name' => $asset,
                    'farm' => $store,
                    'purchase_cost' => $cost,
                    'field_size' => $size,
                    'created_by' => $user,
                    'created_at' => $date
                );
                $add_field = new add_data('fields', $field_data);
                $add_field->create_data();
            }

            echo "<div class='success'><p>$asset added successfully <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }
    