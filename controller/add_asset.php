<?php
date_default_timezone_set("Africa/Lagos");
    session_start();
    $user = $_SESSION['user_id'];
    $asset = strtoupper(htmlspecialchars(stripslashes($_POST['asset'])));
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
    $get_cat_name = new selects();
    $cat_name = $get_cat_name->fetch_details_group('asset_locations', 'location', 'location_id', $location);
    $locate =  $cat_name->location;
    $loc = substr($locate, 0, 3);
    $asset_no = "JEVI/".$loc."/".$asset_year."/".$asset_month."/";
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
        'post_date' => $date,
        'posted_by' => $user,
    );

    //check if department exists
    $check = new selects();
    $results = $check->fetch_count_cond('assets', 'asset', $asset);
    if($results > 0){
        echo "<p class='exist'>$asset already exists</p>";
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
            $new_asset_no = $asset_no.$count;
            $update_asset = new Update_table();
            $update_asset->update('assets', 'asset_no', 'asset_id', $new_asset_no, $asset_id);

            echo "<p>$asset added successfully</p>";
        }
    }
    