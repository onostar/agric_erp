<?php

    $customer = strtoupper(htmlspecialchars(stripslashes($_POST['customer'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes(($_POST['address']))));
    $email = htmlspecialchars(stripslashes(($_POST['email'])));
    // $type = htmlspecialchars(stripslashes(($_POST['customer_type'])));

    $data = array(
        'customer' => $customer,
        'phone_numbers' => $phone,
        'customer_email' => $email,
        'customer_address' => $address
        // 'customer_type' => $type
    );
    $ledger_data = array(
        'account_group' => 1,
        'sub_group' => 1,
        'class' => 4,
        'ledger' => $customer
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";

   //check if customer exists
   $check = new selects();
   $results = $check->fetch_count_cond('customers', 'customer', $customer);
   if($results > 0){
       echo "<p class='exist'><span>$customer</span> already exists!</p>";
   }else{
       //create customer
       $add_data = new add_data('customers', $data);
       $add_data->create_data();
       if($add_data){
             //get customer id
             $get_cust = new selects();
             $cus_id = $get_cust->fetch_lastInserted('customers', 'customer_id');
             $customer_id = $cus_id->customer_id;
             //add to account ledger
             //check if customer is in ledger
             $get_ledger = new selects();
             $ledg = $get_ledger->fetch_count_cond('ledgers', 'ledger', $customer);
             if($ledg > 0){
                 echo "<p class='exist'><span>$customer</span> already exists in ledger</p>";
             }else{
                 $add_ledger = new add_data('ledgers', $ledger_data);
                 $add_ledger->create_data();
                 //update customer ledger no
                 //first get ledger id from ledger table
                 $get_last = new selects();
                 $ids = $get_last->fetch_lastInserted('ledgers', 'ledger_id');
                 $ledger_id = $ids->ledger_id;
                  //update account number
                $acn = "10104".$ledger_id;
                $update_acn = new Update_table();
                $update_acn->update('ledgers', 'acn', 'ledger_id', $acn, $ledger_id);
                 //now update
                 $update = new Update_table();
                 $update->update_double('customers', 'ledger_id', $ledger_id, 'acn', $acn, 'customer_id', $customer_id);
             }
           echo "<p><span>$customer</span> ceated successfully!</p>";
       }
   }