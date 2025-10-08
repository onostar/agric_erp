<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
    $supplier = strtoupper(htmlspecialchars(stripslashes($_POST['supplier'])));
    $contact = ucwords(htmlspecialchars(stripslashes($_POST['contact_person'])));
    $phone = htmlspecialchars(stripslashes(($_POST['phone'])));
    // $email = htmlspecialchars(stripslashes(($_POST['email'])));
    $address = htmlspecialchars(stripslashes(($_POST['address'])));

    $data = array(
        'vendor' => $supplier,
        'contact_person' => $contact,
        'phone' => $phone,
        // 'email_address' => $email
        'biz_address' => $address,
        'added_by' => $user,
        'created_date' => $date
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
   //check if vendor exists in ledger
   $ledg = $check->fetch_count_3cond('ledgers', 'account_group', 2, 'class', 7, 'ledger', $supplier);
   if($results > 0 || $ledg > 0){
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
        
           echo "<p><span>$supplier</span> created successfully!</p>";
       }
   }
}else{
    echo "Your Session has Expired. Please Login to Continue";
    exit();
}