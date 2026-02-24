<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $customer = htmlspecialchars(stripslashes($_POST['staff_id']));
    $beneficiary_id = htmlspecialchars(stripslashes($_POST['beneficiary_id']));
    $beneficiary = strtoupper(htmlspecialchars(stripslashes($_POST['beneficiary'])));
    $phone = htmlspecialchars(stripslashes($_POST['ben_phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes($_POST['ben_address'])));
    $entitlement = htmlspecialchars(stripslashes($_POST['entitlement']));
    $relation = strtoupper(htmlspecialchars(stripslashes($_POST['ben_relationship'])));
    $gender = htmlspecialchars(stripslashes($_POST['ben_gender']));
    $date = date("Y-m-d H:i:s");
    $data = array(
        'staff' => $customer,
        'beneficiary' => $beneficiary,
        'phone' => $phone,
        'address' => $address,
        'entitlement' => $entitlement,
        'relation' => $relation,
        'gender' => $gender,
    );
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    include "../classes/update.php";
    //check if beneficiary already exists
    $check = new selects();
    $results = $check->fetch_count_2cond1neg('beneficiaries', 'beneficiary', $beneficiary, 'staff', $customer, 'beneficiary_id', $beneficiary_id);
    if($results > 0){
        echo "<p class='exist'>$beneficiary already exists for this staff</p>";
    }else{
        //get previous entitlement percentage for beneficiary
        $prev_ent = $check->fetch_details_cond('beneficiaries', 'beneficiary_id', $beneficiary_id);
        foreach($prev_ent as $prev){
            $prev_entitlement = $prev->entitlement;
        }
        //check total entitlement percentage for staff
        $ents = $check->fetch_sum_single('beneficiaries', 'entitlement', 'staff', $customer);
        if(is_array($ents)){
            foreach($ents as $ent){
                $total_entitlement = $ent->total;
            }
        }else{
            $total_entitlement = 0;
        }
        if((($total_entitlement - $prev_entitlement) + $entitlement) > 100){
            echo "<p class='exist'>Total entitlement percentage for this staff cannot exceed 100%. Current total entitlement is $total_entitlement%</p>";
            echo "<script>alert('Error! Total entitlement percentage for this staff cannot exceed 100%. Current total entitlement is $total_entitlement%');</script>";
            include "../controller/beneficiaries.php";

        }else{
        //add beneficiary
        $add_data = new Update_table();
        $add_data->updateAny('beneficiaries', $data, 'beneficiary_id', $beneficiary_id);
        if($add_data){
            echo "<p class='notify' style='color:#fff;'>$beneficiary details updated successfully</p>";
            include "../controller/beneficiaries.php";
        ?>
        
<?php
        }
        }
    }