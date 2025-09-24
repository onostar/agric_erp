<?php

    $supplier = strtoupper(htmlspecialchars(stripslashes($_POST['supplier'])));
    $contact = ucwords(htmlspecialchars(stripslashes($_POST['contact_person'])));
    $phone = htmlspecialchars(stripslashes(($_POST['phone'])));
    $email = htmlspecialchars(stripslashes(($_POST['email'])));

    $data = array(
        'vendor' => $supplier,
        'contact_person' => $contact,
        'phone' => $phone,
        'email_address' => $email
    );
    $ledger_data = array(
        'account_group' => 2,
        'sub_group' => 3,
        'class' => 7,
        'ledger' => $supplier
    );

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";

   //check if vendor already exists
   $check = new selects();
   $results = $check->fetch_count_cond('vendors', 'vendor', $supplier);
   if($results > 0){
       echo "<p class='exist'><span>$supplier</span> already exists</p>";
   }else{
       //add reason
       $add_data = new add_data('vendors', $data);
       $add_data->create_data();
       if($add_data){
            //get vndor id
            $get_vend = new selects();
            $vend_id = $get_vend->fetch_lastInserted('vendors', 'vendor_id');
            $vendor_id = $vend_id->vendor_id;
            //add to account ledger
            //check if vendor is in ledger
            $get_ledger = new selects();
            $ledg = $get_ledger->fetch_count_cond('ledgers', 'ledger', $supplier);
            if($ledg > 0){
                echo "<p class='exist'><span>$supplier</span> already exists in ledger</p>";
            }else{
                $add_ledger = new add_data('ledgers', $ledger_data);
                $add_ledger->create_data();
                //update vendor ledger no
                //first get ledger id from ledger table
                $get_last = new selects();
                $ids = $get_last->fetch_lastInserted('ledgers', 'ledger_id');
                $ledger_id = $ids->ledger_id;
                //update account number
                $acn = "20307".$ledger_id;
                $update_acn = new Update_table();
                $update_acn->update('ledgers', 'acn', 'ledger_id', $acn, $ledger_id);
                //now update
                $update = new Update_table();
                $update->update_double('vendors', 'ledger_id',  $ledger_id, 'account_no', $acn, 'vendor_id', $vendor_id);
            }
            
            
           echo "<p><span>$supplier</span> created successfully!</p>";
       }
   }