<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();    
        $user = $_SESSION['user_id'];
    // if(isset($_POST['change_prize'])){
        $supplier = htmlspecialchars(stripslashes($_POST['suppliers']));
        $invoice = htmlspecialchars(stripslashes($_POST['sales_invoice']));
        $waybill = htmlspecialchars(stripslashes($_POST['waybill']));
        $date = date("Y-m-d H:i:s");

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/update.php";

        //get invoice details
        $get_invoice = new selects();
        $invs = $get_invoice->fetch_sum_2col2cond('purchases', 'cost_price', 'quantity', 'vendor', $supplier, 'invoice', $invoice);
        foreach($invs as $inv){
            $invoice_amount = $inv->total;
        }
        //get invoice store
        $get_store = new selects();
        $str = $get_store->fetch_details_2cond('purchases', 'vendor', 'invoice', $supplier, $invoice);
        foreach($str as $st){
            $store = $st->store;
        }
        /* $todays_date = date("dmyhis");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $trx_num = "TR".$ran_num.$todays_date;
        $data = array(
            'invoice' => $invoice,
            'vendor' => $supplier,
            'waybill' => $waybill,
            'invoice_amount' => $invoice_amount,
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store,
            'trx_number' => $trx_num

        );
        $add_waybill = new add_data('waybills', $data);
        $add_waybill->create_data(); */
        //update invoice
            $update_debt = new Update_table();
            $update_debt->update2cond('purchases', 'waybill', 'vendor', 'invoice', $waybill, $supplier, $invoice);
        
        
        // if($update_debt){
             echo "<div class='success'><p>Items Received successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        } */
    // }