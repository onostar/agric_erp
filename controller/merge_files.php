<?php
    session_start();
    $correct_cus = htmlspecialchars(stripslashes($_POST['correct_customer']));
    $wrong_cus = htmlspecialchars(stripslashes($_POST['wrong_customer']));
    include "../classes/dbh.php";
    include "../classes/update.php";
    include "../classes/delete.php";
    //update across all tables

    //get all tables
    /* $get_tables = new selects();
    $tables = $get_tables->fetch_tables('tonnac_accounting');
    foreach($tables as $table){
        //check for customer number in each table and delete it when thenumber is seen
        $check_column = new selects();
        $cols = $check_column->fetch_column($table->table_name, 'trx_number');
        if($cols){
            $delete_tx = new deletes();
            $delete_tx->delete_item($table->table_name, 'trx_number', $trx_number);
        }
        
    } */
    //customer_trail
    $change_customer = new Update_table();
    $change_customer->mergeCustomer('customer_trail', $correct_cus, $wrong_cus);
    //debtors
    $change_customer = new Update_table();
    $change_customer->mergeCustomer('debtors', $correct_cus, $wrong_cus);
    //deposits
    $change_customer = new Update_table();
    $change_customer->mergeCustomer('deposits',$correct_cus, $wrong_cus);
    //outstanding
    /* $change_customer = new Update_table();
    $change_customer->mergeCustomer('outstanding', $correct_cus, $wrong_cus); */
    //payments
    $change_customer = new Update_table();
    $change_customer->mergeCustomer('payments',$correct_cus, $wrong_cus);
    //sales
    $change_customer = new Update_table();
    $change_customer->mergeCustomer('sales', $correct_cus, $wrong_cus);
    if($change_customer){
        $delete_customer = new deletes();
        $delete_customer->delete_item('customers', 'customer_id', $wrong_cus);
        echo "<div class='success'><p>Customer files merged successfully! <i class='fas fa-thumbs-up'></i></p></div>";
   }else{
       echo "<p style='background:red; color:#fff; padding:5px'>Failed to Merge Files <i class='fas fa-thumbs-down'></i></p>";
   }