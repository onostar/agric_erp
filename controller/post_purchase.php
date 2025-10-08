<?php
date_default_timezone_set("Africa/Lagos");
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/update.php";
include "../classes/inserts.php";
    session_start();
    if(isset($_SESSION['user_id'])){
        $trans_type = "purchases";
            $user = $_SESSION['user_id'];
            $invoice = $_POST['purchase_invoice'];
            $payment_type = htmlspecialchars(stripslashes($_POST['payment_type']));
            $waybill = htmlspecialchars(stripslashes($_POST['waybill']));
            $deposit = htmlspecialchars(stripslashes($_POST['deposit_amount']));
            $total_amount = htmlspecialchars(stripslashes($_POST['total_amount']));
            $vendor = htmlspecialchars(stripslashes($_POST['vendor']));
            $store = htmlspecialchars(stripslashes($_POST['store']));
            $contra = htmlspecialchars(stripslashes($_POST['contra']));
            $type = "Wholesale";
            $details = "Inventory Purchase";
            $date = date("Y-m-d H:i:s");
            $grand_total = intval($total_amount);
             //generate transaction number
            //get current date
            $todays_date = date("dmyhis");
            $ran_num ="";
            for($i = 0; $i < 3; $i++){
                $random_num = random_int(0, 9);
                $ran_num .= $random_num;
            }
            $trx_num = "TR".$ran_num.$todays_date;
            //get invoice details
            $get_details = new selects();
            $invoices = $get_details->fetch_details_cond('purchases', 'invoice', $invoice);
            foreach($invoices as $receipt){
                // $trans_date = $receipt->purchase_date;
                $trans_date = $date;

            }
            //get vendor details
            $vends = $get_details->fetch_details_cond('vendors', 'vendor_id', $vendor);
            foreach($vends as $vend){
                $vendor_name = $vend->vendor;
                $balance = $vend->balance;
                $vendor_ledger = $vend->account_no;
            }
            
            //get ledger type
            $types = $get_details->fetch_details_cond('ledgers', 'acn', $vendor_ledger);
            foreach($types as $type){
                $vendor_type = $type->account_group;
                $vendor_sub = $type->sub_group;
                $vendor_class = $type->class;
            };
            if($payment_type == "Full payment"){
                $amount_paid = $grand_total;
            }elseif($payment_type == "Deposit"){
                $amount_paid = $deposit;
            }else{
                $amount_paid = 0;
            }
            //get contra ledger details
            $cons = $get_details->fetch_details_cond('ledgers', 'ledger_id', $contra);
            if(is_array($cons)){
                foreach($cons as $con){
                    $contra_ledger = $con->acn;
                    $contra_type = $con->account_group;
                    $contra_sub_group = $con->sub_group;
                    $contra_class = $con->class;
                    $contra_name = $con->ledger;
                }
            }
            //insert into purchase payment
            $data = array(
                'vendor' => $vendor,
                'invoice' => $invoice,
                'product_cost' => $total_amount,
                'waybill' => $waybill,
                'amount_due' => $total_amount,
                'amount_paid' => $amount_paid,
                'payment_mode' => $payment_type,
                'posted_by' => $user,
                'store' => $store,
                'trans_date' => $trans_date,
                'post_date' => $date,
                'trx_number' => $trx_num
            );
            $add_payment = new add_data('purchase_payments', $data);
            $add_payment->create_data();
            //check if payment is made a and  add to vendor payment
            if($payment_type != "Credit"){
                if($contra_name == "CASH ACCOUNT"){
                    $mode = "Cash";
                }else{
                    $mode = "Bank";
                }
                //insert into vendor payment
                $vendor_data = array(
                    'vendor' => $vendor,
                    'amount' => $amount_paid,
                    'contra' => $contra,
                    'payment_mode' => $mode,
                    'posted_by' => $user,
                    'store' => $store,
                    'post_date' => $date,
                    'trans_date' => $trans_date,
                    'trx_number' => $trx_num
                );
                $add_vendor_pay = new add_data('vendor_payments', $vendor_data);
                $add_vendor_pay->create_data();
            }            
            if($add_payment){
                //update purchase status and waybill
                $update_status = new Update_table();
                $update_status->update_double2Cond('purchases', 'purchase_status', 1, 'waybill', $waybill, 'vendor', $vendor, 'invoice', $invoice);
                //check if invoice exist in waybill
                $bills = $get_details->fetch_count_2cond('waybills', 'invoice', $invoice, 'vendor', $vendor);
                if($bills > 0 ){
                    //update way bill on waybil table
                    $update_status = new Update_table();
                    $update_status->update_double2cond('waybills', 'waybill', $waybill, 'trx_number', $trx_num, 'vendor', $vendor,'invoice', $invoice);
                }else{
                    //insert into waybill table
                    $data = array(
                        'invoice' => $invoice,
                        'vendor' => $vendor,
                        'waybill' => $waybill,
                        'invoice_amount' => $total_amount,
                        'trx_number' => $trx_num,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'store' => $store,
                    );
                    $add_waybill = new add_data('waybills', $data);
                    $add_waybill->create_data();
                }
                //insert into transaction table
                //get inventory legder id
                $invs = $get_details->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
                foreach($invs as $inv){
                    $inventory_ledger = $inv->acn;
                    $inv_type = $inv->account_group;
                    $inv_sub_group = $inv->sub_group;
                    $inv_class = $inv->class;

                }
                
                
                if($payment_type == "Credit"){
                    //debit inventory of invoice amount
                    $debit_data = array(
                        'account' => $inventory_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_sub_group,
                        'class' => $inv_class,
                        'debit' => $total_amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'store' => $store
                    );
                    $credit_data = array(
                        'account' => $vendor_ledger,
                        'account_type' => $vendor_type,
                        'sub_group' => $vendor_sub,
                        'class' => $vendor_class,
                        'credit' => $total_amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'store' => $store
                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data();
                }elseif($payment_type == "Deposit"){
                    //debit inventory of invoice amount
                    $debit_inv = array(
                        'account' => $inventory_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_sub_group,
                        'class' => $inv_class,
                        'debit' => $total_amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'store' => $store

                    );
                    //add debit
                    $add_inv_debit = new add_data('transactions', $debit_inv);
                    $add_inv_debit->create_data();
                    //credit vendor of invoice amount
                    $credit_vendor = array(
                        'account' => $vendor_ledger,
                        'account_type' => $vendor_type,
                        'sub_group' => $vendor_sub,
                        'class' => $vendor_class,
                        'credit' => $total_amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'store' => $store
                    );
                    //add credit
                    $add_vendor_credit = new add_data('transactions', $credit_vendor);
                    $add_vendor_credit->create_data();
                    //debit vendor of deposit amount
                    $debit_vendor = array(
                        'account' => $vendor_ledger,
                        'account_type' => $vendor_type,
                        'sub_group' => $vendor_sub,
                        'class' => $vendor_class,
                        'debit' => $deposit,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => 'Deposit for '.$details,
                        'trans_date' => $trans_date,
                        'store' => $store
                    );
                    //add debit
                    $add_vendor_debit = new add_data('transactions', $debit_vendor);
                    $add_vendor_debit->create_data();
                    //credit cash/bank of deposit amount
                    $credit_contra = array(
                        'account' => $contra_ledger,
                        'account_type' => $contra_type,
                        'sub_group' => $contra_sub_group,
                        'class' => $contra_class,
                        'credit' => $deposit,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => 'Deposit for '.$details,
                        'trans_date' => $trans_date,
                        'store' => $store
                    );
                    //add credit
                    $add_contra_credit = new add_data('transactions', $credit_contra);
                    $add_contra_credit->create_data();
                    //cash flow data
                    $flow_data = array(
                        'account' => $contra_ledger,
                        'destination' => $vendor_ledger,
                        'details' => 'inventory purchase',
                        'trx_number' => $trx_num,
                        'amount' => $deposit,
                        'trans_type' => 'outflow',
                        'activity' => 'operating',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'store' => $store
                    );
                    $add_flow = new add_data('cash_flows', $flow_data);
                    $add_flow->create_data();
                }else{
                    //debit inventory of invoice amount
                    $debit_data = array(
                        'account' => $inventory_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_sub_group,
                        'class' => $inv_class,
                        'debit' => $total_amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'store' => $store

                    );
                    //credit contra account of invoice amount
                    $credit_data = array(
                        'account' => $contra_ledger,
                        'account_type' => $contra_type,
                        'sub_group' => $contra_sub_group,
                        'class' => $contra_class,
                        'credit' => $total_amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'store' => $store

                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data();

                    //cash flow data
                    $flow_data = array(
                        'account' => $contra_ledger,
                        'destination' => $inventory_ledger,
                        'details' => 'inventory purchase',
                        'trx_number' => $trx_num,
                        'amount' => $total_amount,
                        'trans_type' => 'outflow',
                        'activity' => 'operating',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'store' => $store
                    );
                    $add_flow = new add_data('cash_flows', $flow_data);
                    $add_flow->create_data();
                }
                
                //update invoice with trxnumber
                $update_invoice = new Update_table();
                $update_invoice->update2cond('purchases', 'trx_number', 'vendor', 'invoice', $trx_num, $vendor, $invoice);
                echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }
            
                
?>
<?php
 
    }else{
        echo "Your session has expired. Please log in again";
        exit();
        // header("Location: ../index.php");
    } 
?>