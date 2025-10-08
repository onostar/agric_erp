<?php
    $vendor_id = htmlspecialchars(stripslashes($_POST['vendor_id']));
    $vendor = strtoupper(htmlspecialchars(stripslashes($_POST['vendor'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $contact = ucwords(htmlspecialchars(stripslashes(($_POST['contact']))));
    $address = htmlspecialchars(stripslashes(($_POST['address'])));
    // $email = htmlspecialchars(stripslashes(($_POST['email'])));

   
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";
    include "../classes/select.php";
    //get other vendor details
    $get_details = new selects();
    $rows = $get_details->fetch_details_cond('vendors', 'vendor_id', $vendor_id);
    foreach($rows as $row){
        $ledger_id = $row->ledger_id;
    }
    $data = array(
        'vendor' => $vendor,
        'phone' => $phone,
        'contact_person' => $contact,
        // 'email_address' => $email,
        'biz_address' => $address
    );
    //update vendor
    $update_data = new Update_table();
    $update_data->updateAny('vendors', $data, 'vendor_id', $vendor_id);
    //update ledger
    $update_data->update('ledgers', 'ledger', 'ledger_id', $vendor, $ledger_id);
    if($update_data){
        echo "<div class='success'><p>$vendor</span> details updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
   