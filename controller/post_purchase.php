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
            $grand_total = intval($total_amount) + intval($waybill);
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
            $get_invoice = new selects();
            $invoices = $get_invoice->fetch_details_cond('purchases', 'invoice', $invoice);
            foreach($invoices as $receipt){
                // $trans_date = $receipt->purchase_date;
                $trans_date = $date;

            }
            //get vendor details
            $get_vendor = new selects();
            $vends = $get_vendor->fetch_details_cond('vendors', 'vendor_id', $vendor);
            foreach($vends as $vend){
                $vendor_name = $vend->vendor;
                $balance = $vend->balance;
                $vendor_ledger = $vend->account_no;
            }
            
            //get ledger type
            $get_type = new selects();
            $types = $get_type->fetch_details_cond('ledgers', 'acn', $vendor_ledger);
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
             
            //insert into purchase payment
            $data = array(
                'vendor' => $vendor,
                'invoice' => $invoice,
                'product_cost' => $total_amount,
                'waybill' => $waybill,
                'amount_due' => $grand_total,
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
               
            if($add_payment){
                //update purchase status and waybill
                $update_status = new Update_table();
                $update_status->update_double2Cond('purchases', 'purchase_status', 1, 'waybill', $waybill, 'vendor', $vendor, 'invoice', $invoice);
                //check if invoice exist in waybill
                $get_waybill = new selects();
                $bills = $get_waybill->fetch_count_2cond('waybills', 'invoice', $invoice, 'vendor', $vendor);
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
                $get_inv = new selects();
                $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
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
                        'debit' => $grand_total,
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
                        'credit' => $grand_total,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date
                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data();
                }elseif($payment_type == "Deposit"){
                    $amount = $deposit;
                    $debit_data = array(
                        'account' => $vendor_ledger,
                        'account_type' => $vendor_type,
                        'sub_group' => $vendor_sub,
                        'class' => $vendor_class,
                        'debit' => $amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'trans_date' => $trans_date
                    );
                    $credit_data = array(
                        'account' => $inventory_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_sub_group,
                        'class' => $inv_class,
                        'credit' => $amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date,
                        'trans_date' => $trans_date

                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data();

                    //cash flow data
                    $flow_data = array(
                        'account' => $inventory_ledger,
                        'details' => 'inventory purchase',
                        'trx_number' => $trx_num,
                        'amount' => $amount,
                        'trans_type' => 'outflow',
                        'activity' => 'operating',
                        'post_date' => $date,
                        'posted_by' => $user
                    );
                    $add_flow = new add_data('cash_flows', $flow_data);
                    $add_flow->create_data();
                }else{
                    $amount = $grand_total;
                    //get contra ledger details
                    $get_inv = new selects();
                    $invs = $get_inv->fetch_details_cond('ledgers', 'ledger_id', $contra);
                    foreach($invs as $inv){
                        $contra_ledger = $inv->acn;
                        $contra_type = $inv->account_group;
                        $contra_sub_group = $inv->sub_group;
                        $contra_class = $inv->class;

                    }
                    //get inventory legder id
                    $get_inv = new selects();
                    $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
                    foreach($invs as $inv){
                        $inventory_ledger = $inv->acn;
                        $inv_type = $inv->account_group;
                        $inv_sub_group = $inv->sub_group;
                        $inv_class = $inv->class;

                    }
                    $debit_data = array(
                        'account' => $inventory_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_sub_group,
                        'class' => $inv_class,
                        'debit' => $amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date

                    );
                    $credit_data = array(
                        'account' => $contra_ledger,
                        'account_type' => $contra_type,
                        'sub_group' => $contra_sub_group,
                        'class' => $contra_class,
                        'credit' => $amount,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'details' => $details,
                        'trans_date' => $trans_date

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
                        'details' => 'inventory purchase',
                        'trx_number' => $trx_num,
                        'amount' => $amount,
                        'trans_type' => 'outflow',
                        'activity' => 'operating',
                        'post_date' => $date,
                        'posted_by' => $user
                    );
                    $add_flow = new add_data('cash_flows', $flow_data);
                    $add_flow->create_data();
                }
                

                    //cost of sales data
                    //get current date
                    /* $todays_date = date("dmyhis");
                    $ran_num ="";
                    for($i = 0; $i < 3; $i++){
                        $random_num = random_int(0, 9);
                        $ran_num .= $random_num;
                    }
                    $trx_num = "TR".$ran_num.$todays_date; */
                    /* $amount = $grand_total;
                    $cos_data = array(
                        'posted_by' => $user,
                        'trans_date' => $date,
                        'store' => $store,
                        'amount' => $total_amount,
                        'details' => 'cost of sales',
                        'post_date' => $date,
                        'trx_number' => $trx_num
                    );
                    //get ledger account numbers and account type
                    $get_exp = new selects();
                    $exps = $get_exp->fetch_details_cond('ledgers', 'ledger', 'COST OF SALES');
                    foreach($exps as $exp){
                        $cos_ledger = $exp->acn;
                        $cos_type = $exp->account_group;
                        $cos_group = $exp->sub_group;
                        $cos_class = $exp->class;
                    }
                    //get contra ledger account number
                    $get_contra = new selects();
                    $cons = $get_contra->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
                    foreach($cons as $con){
                        $inv_ledger = $con->acn;
                        $inv_type = $con->account_group;
                        $inv_group = $con->sub_group;
                        $inv_class = $con->class;
                    }
                    //post INVENTORIES
                    $add_data = new add_data('cost_of_sales', $cos_data);
                    $add_data->create_data(); */

                    //insert into transaction table
                /* $debit_data = array(
                    'account' => $cos_ledger,
                    'account_type' => $cos_type,
                    'sub_group' => $cos_group,
                    'class' => $cos_class,
                    'debit' => $total_amount,
                    'details' => 'Cost of sales',
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trans_date

                );
                $credit_data = array(
                    'account' => $inv_ledger,
                    'account_type' => $inv_type,
                    'sub_group' => $inv_group,
                    'class' => $inv_class,
                    'credit' => $total_amount,
                    'details' => 'Cost of sales',
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trans_date

                ); */
                //add debit
               /*  $add_debit = new add_data('transactions', $debit_data);
                $add_debit->create_data();      
                //add credit
                $add_credit = new add_data('transactions', $credit_data);
                $add_credit->create_data();  */
                //update invoice with trxnumber
                $update_invoice = new Update_table();
                $update_invoice->update2cond('purchases', 'trx_number', 'vendor', 'invoice', $trx_num, $vendor, $invoice);
                echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }
            
                
?>
<?php
 
    }else{
        header("Location: ../index.php");
    } 
?>