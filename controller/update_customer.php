<?php
    $customer_id = htmlspecialchars(stripslashes($_POST['customer_id']));
    $customer = strtoupper(htmlspecialchars(stripslashes($_POST['customer'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes($_POST['address'])));
    $email = strtolower(htmlspecialchars(stripslashes($_POST['email'])));
    $type = htmlspecialchars(stripslashes($_POST['customer_type']));
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";
    include "../classes/select.php";
    $update_data = new Update_table();

    //get customer details
    $dets = new selects();
    $rows = $dets->fetch_details_cond('customers', 'customer_id', $customer_id);
    foreach($rows as $row){
        $password = $row->user_password;
        $ledger = $row->ledger_id;
    }
    //check if customer exists
    $results = $dets->fetch_count_cond1neg('customers', 'customer', $customer,'customer_id', $customer_id);
    $results2 = $dets->fetch_count_cond1neg('customers', 'customer_email', $email, 'customer_id', $customer_id);
    $results3 = $dets->fetch_count_cond1neg('customers', 'phone_numbers', $phone, 'customer_id', $customer_id);
    //checkledger
    $ledg = $dets->fetch_count_cond1neg('ledgers', 'ledger', $customer, 'ledger_id', $ledger);
    if($results > 0 || $results3 > 0){
        echo "<p class='exist'><span>$customer</span> already exists!</p>";
        exit;
    }elseif($results2 > 0){
        echo "<p class='exist'><span>$email</span> already taken, try another email address</p>";
        exit;
    }elseif($results3 > 0){
        echo "<p class='exist'>Phone number already exists iin our database, try another phone number</p>";
        exit;
    }elseif($ledg > 0){
        echo "<p class='exist'><span>$customer</span> already exists in ledger</p>";
    }else{
        if($type == "Landowner" && empty($password)){
            $new_password = 123;
            $update_data->update('customers', 'user_password', 'customer_id', $new_password, $customer_id);
        }
        //update customer
        $update_data->update_quadruple('customers', 'customer', $customer, 'phone_numbers',$phone, 'customer_address', $address, 'customer_email', $email, 'customer_id', $customer_id);
        if($update_data){
            //update in ledger
            $update_data->update('ledgers', 'ledger','ledger_id', $customer, $ledger);
            echo "<div class='success'><p>$customer</span> details updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }
   