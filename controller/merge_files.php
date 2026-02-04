<?php
    session_start();
    $correct_cus = htmlspecialchars(stripslashes($_POST['correct_customer']));
    $wrong_cus = htmlspecialchars(stripslashes($_POST['wrong_customer']));
    include "../classes/dbh.php";
    include "../classes/update.php";
    include "../classes/delete.php";
    include "../classes/select.php";
    //update across all tables
    //customer_trail
    $change_customer = new Update_table();
    $change_customer->mergeCustomer('customer_trail', $correct_cus, $wrong_cus);
    //debtors
    $change_customer->mergeCustomer('debtors', $correct_cus, $wrong_cus);
    //deposits
    $change_customer->mergeCustomer('deposits',$correct_cus, $wrong_cus);
    //outstanding
    // $change_customer->mergeCustomer('outstanding', $correct_cus, $wrong_cus);
    //payments
    $change_customer->mergeCustomer('payments',$correct_cus, $wrong_cus);
    //sales
    $change_customer->mergeCustomer('sales', $correct_cus, $wrong_cus);
    $change_customer->mergeCustomer('assigned_fields', $correct_cus, $wrong_cus);
    $change_customer->mergeCustomer('rent_schedule', $correct_cus, $wrong_cus);
    $change_customer->mergeCustomer('rent_payments', $correct_cus, $wrong_cus);
    $change_customer->mergeCustomer('document_uploads', $correct_cus, $wrong_cus);
    $change_customer->mergeCustomer('field_payments', $correct_cus, $wrong_cus);
    $change_customer->mergeCustomer('field_payment_schedule', $correct_cus, $wrong_cus);
    //invoicing
    // $change_customer->mergeCustomer('invoices', $correct_cus, $wrong_cus);
    //ledger and transactions
    //get ledgers
    $get_cor_ledger = new selects();
    $cors = $get_cor_ledger->fetch_details_group('customers', 'acn', 'customer_id', $correct_cus);
    $corect_ledger = $cors->acn;
    $get_wro_ledger = new selects();
    $wros = $get_wro_ledger->fetch_details_group('customers', 'acn', 'customer_id', $wrong_cus);
    $wrong_ledger = $wros->acn;
    //update transaction table
    // $change_customer = new Update_table();
    $change_customer->mergeledger($correct_cus, $wrong_cus);
    if($change_customer){
        //delete from customer table
        $delete_customer = new deletes();
        $delete_customer->delete_item('customers', 'customer_id', $wrong_cus);
        //delete from ledger table
       /*  $delete_customer = new deletes();
        $delete_customer->delete_item('ledegrs', 'acn', $wrong_ledger); */
        echo "<div class='success'><p>Customer files merged successfully! <i class='fas fa-thumbs-up'></i></p></div>";
   }else{
       echo "<p style='background:red; color:#fff; padding:5px'>Failed to Merge Files <i class='fas fa-thumbs-down'></i></p>";
   }