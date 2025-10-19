<?php
// instantiate class
include "../classes/dbh.php";
include "../classes/update.php";
include "../classes/select.php";
include "../classes/inserts.php";
date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $trans_type = "sales";
        $user = $_SESSION['user_id'];
        $invoice = htmlspecialchars(stripslashes($_POST['sales_invoice']));
        $payment_type = htmlspecialchars(stripslashes($_POST['payment_type']));
        $bank = htmlspecialchars(stripslashes($_POST['bank']));
        $cash = htmlspecialchars(stripslashes(floatval($_POST['multi_cash'] ?? 0)));
        $pos = htmlspecialchars(stripslashes(floatval($_POST['multi_pos'] ?? 0)));
        $transfer = htmlspecialchars(stripslashes(floatval($_POST['multi_transfer'] ?? 0)));
        $discount = htmlspecialchars(stripslashes(floatval($_POST['discount'] ?? 0)));
        $store = htmlspecialchars(stripslashes($_POST['store']));
        $type = "Wholesale";
        $wallet = htmlspecialchars(stripslashes(floatval($_POST['wallet'])));
        $deposit = htmlspecialchars(stripslashes(floatval($_POST['deposit'] ?? 0)));
        $contra = htmlspecialchars(stripslashes($_POST['contra']));
        $customer = htmlspecialchars(stripslashes($_POST['customer_id']));
        $date = date("Y-m-d H:i:s");
        $trx_date = $date;
        //generate transaction number
        //get current date
        $todays_date = date("dmyhis");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $trx_num = "TR".$ran_num.$todays_date;
            // $date = htmlspecialchars(stripslashes($_POST['post_date']));

            //get amount due
            $get_details = new selects();
            $amts = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
            foreach($amts as $amt){
                // $amount_due = $amt->amount_due;
                // $wallet = $amt->wallet_balance;
                $customer_ledger = $amt->acn;

            }
            
            //get customer leger type
            $cusl = $get_details->fetch_details_cond('ledgers', 'acn', $customer_ledger);
            foreach($cusl as $cus){
                $customer_type = $cus->account_group;
                $sub_group = $cus->sub_group;
                $class = $cus->class;
            }
            //insert into audit trail
            //get items and quantity sold in the invoice
            $items = $get_details->fetch_details_cond('sales', 'invoice', $invoice);
            foreach($items as $item){
                $all_item = $item->item;
                $sold_qty = $item->quantity;
                //get item previous quantity in inventory
                $prev_qtys = $get_details->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $all_item);
                foreach($prev_qtys as $prev_qty){    
                    //insert into audit trail
                    $audit_data = array(
                        'item' => $all_item,
                        'transaction' => $trans_type,
                        'previous_qty' => $prev_qty->total,
                        'quantity' => $sold_qty,
                        'posted_by' => $user,
                        'store' => $store,
                        'post_date' => $date
                    );
                    $insert_trail = new add_data('audit_trail', $audit_data);
                    $insert_trail->create_data();
                }
            }
            

        //update all items with this invoice
        $update_invoice = new Update_table();
        $update_invoice->update_double('sales', 'sales_status', 2, 'post_date', $date, 'invoice', $invoice);
        //update quantity of the items in inventory
        //get all items first in the invoice
        $rows = $get_details->fetch_details_cond('sales', 'invoice', $invoice);
        
        foreach($rows as $row){
            //update individual quantity in inventory
            $update_invoice->update_inv_qty($row->quantity, $row->item, $store);
            
        }
        
            if($update_invoice){
                //insert into transaction table     

                //insert payment details into payment table
                //get invoice total amount
                $results = $get_details->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                foreach($results as $result){
                    $inv_amount = floatval($result->total);
                }
                //total invoice amount
                $total_amount = floatval($inv_amount) - floatval($discount);
                //get amount paid
                if($payment_type === "Credit"){
                    $amount_paid = 0;
                }elseif($payment_type === "Deposit"){
                    $amount_paid = floatval($deposit);
                }elseif($payment_type === "Wallet"){
                    // wallet must have enough balance to cover full invoice (per your design)
                    $amount_paid = floatval($inv_amount) - floatval($discount);
                    // or simply $amount_paid = floatval($inv_amount); if discount handled separately
                }else{
                    // Cash, POS, Transfer, Multiple etc. — amount_paid should be sum of payment parts or inv_amount - discount
                    $amount_paid = floatval($inv_amount) - floatval($discount);
                }

                //get income legder id
                $incs = $get_details->fetch_details_cond('ledgers', 'ledger', 'GENERAL REVENUE');
                foreach($incs as $inc){
                    $income_ledger = $inc->acn;
                    $income_type = $inc->account_group;
                    $income_group = $inc->sub_group;
                    $income_class = $inc->class;
                }
                //credit income ledger
                $income_data = array(
                    'account' => $income_ledger,
                    'account_type' => $income_type,
                    'sub_group' => $income_group,
                    'class' => $income_class,
                    'details' => 'Sales of product',
                    'credit' => $total_amount,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trx_date,
                    'store' => $store
                );
                //add income credit
                $add_income_credit = new add_data('transactions', $income_data);
                $add_income_credit->create_data();
                /* cost of sales */
                $coss = $get_details->fetch_sum_single('sales', 'cost', 'invoice', $invoice);
                foreach($coss as $costs){
                    $total_cost = $costs->total;
                }
                $cos_data = array(
                    'posted_by' => $user,
                    'trans_date' => $date,
                    'store' => $store,
                    'amount' => $total_cost,
                    'details' => 'cost of sales',
                    'post_date' => $date,
                    'trx_number' => $trx_num
                );
                //post to cost of sales table
                $add_data = new add_data('cost_of_sales', $cos_data);
                $add_data->create_data();
                //get ledger account numbers and account type for cost of sales
                $exps = $get_details->fetch_details_cond('ledgers', 'ledger', 'COST OF SALES');
                foreach($exps as $exp){
                    $cos_ledger = $exp->acn;
                    $cos_type = $exp->account_group;
                    $cos_group = $exp->sub_group;
                    $cos_class = $exp->class;
                }
                //debit cost of goods sold
                $debit_cog = array(
                    'account' => $cos_ledger,
                    'account_type' => $cos_type,
                    'sub_group' => $cos_group,
                    'class' => $cos_class,
                    'debit' => $total_cost,
                    'details' => 'Cost of sales',
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trx_date,
                    'store' => $store

                );
                 //add debit
                $add_cog_debit = new add_data('transactions', $debit_cog);
                $add_cog_debit->create_data();
                //get inventory ledger account number
                $cons = $get_details->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
                foreach($cons as $con){
                    $inv_ledger = $con->acn;
                    $inv_type = $con->account_group;
                    $inv_group = $con->sub_group;
                    $inv_class = $con->class;
                }
                //credit inventory
                $credit_inv = array(
                    'account' => $inv_ledger,
                    'account_type' => $inv_type,
                    'sub_group' => $inv_group,
                    'class' => $inv_class,
                    'credit' => $total_cost,
                    'details' => 'Cost of sales',
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trx_date,
                    'store' => $store

                );
                //add credit inventory
                $add_credit_inv = new add_data('transactions', $credit_inv);
                $add_credit_inv->create_data();
                //debit customer account
                $customer_debit = array(
                    'account' => $customer_ledger,
                    'account_type' => $customer_type,
                    'sub_group' => $sub_group,
                    'class' => $class,
                    'details' => 'Goods purchased',
                    'debit' => $total_amount,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trx_date,
                    'store' => $store
                );
                //add customer debit for credit bought
                $add_customer_debit = new add_data('transactions', $customer_debit);
                $add_customer_debit->create_data();
                //check if payment was from customer wallet
                if($payment_type == "Wallet"){
                    //credit customer account
                    $customer_credit = array(
                        'account' => $customer_ledger,
                        'account_type' => $customer_type,
                        'sub_group' => $sub_group,
                        'class' => $class,
                        'details' => 'Goods purchased',
                        'credit' => $amount_paid,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date,
                        'store' => $store
                    );
                    //add customer credit for wallet payment
                    $add_customer_credit = new add_data('transactions', $customer_credit);
                    $add_customer_credit->create_data();
                }
                /* check if money was paid*/
                if($payment_type !== "Credit" && $payment_type !== "Multiple" && $payment_type !== "Wallet"){
                    //get payment ledgers
                    if($payment_type == "Cash"){
                        $ledger_name = "CASH ACCOUNT";
                    }elseif($payment_type == "Deposit"){
                        if($contra == "Cash"){
                            $ledger_name = "CASH ACCOUNT";
                        }else{
                            //get bank
                            $bnk = $get_details->fetch_details_group('banks', 'bank', 'bank_id', $contra);
                            $ledger_name = $bnk->bank;
                        }
                    }else{
                        //get bank
                        $bnk = $get_details->fetch_details_group('banks', 'bank', 'bank_id', $bank);
                        $ledger_name = $bnk->bank;
                    }
                    //get contra ledger details
                    $invs = $get_details->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                    foreach($invs as $inv){
                        $dr_ledger = $inv->acn;
                        $dr_type = $inv->account_group;
                        $dr_group = $inv->sub_group;
                        $dr_class = $inv->class;
                    }
                    //cash flow data
                    $flow_data = array(
                        'account' => $dr_ledger,
                        'destination' => $customer_ledger,
                        'details' => 'Net Income',
                        'trx_number' => $trx_num,
                        'amount' => $amount_paid,
                        'trans_type' => 'inflow',
                        'activity' => 'operating',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'store' => $store
                    );
                    $add_flow = new add_data('cash_flows', $flow_data);
                    $add_flow->create_data();
                    /* add payment to transactions */
                    $debit_payment = array(
                        'account' => $dr_ledger,
                        'account_type' => $dr_type,
                        'sub_group' => $dr_group,
                        'class' => $dr_class,
                        'details' => 'Payment for goods sold',
                        'debit' => $amount_paid,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date,
                        'store' => $store
            
                    );
                    //debit cash or bank
                    $add_pay_debit = new add_data('transactions', $debit_payment);
                    $add_pay_debit->create_data();
                     //credit customer account
                    $customer_credit = array(
                        'account' => $customer_ledger,
                        'account_type' => $customer_type,
                        'sub_group' => $sub_group,
                        'class' => $class,
                        'details' => 'Goods purchased',
                        'credit' => $amount_paid,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date,
                        'store' => $store
                    );
                    //add customer credit for wallet payment
                    $add_customer_credit = new add_data('transactions', $customer_credit);
                    $add_customer_credit->create_data();
                }
                
                //update invoice with trxnumber
                $update_invoice->update('sales', 'trx_number', 'invoice', $trx_num, $invoice);
                //insert payments
                if($payment_type == "Multiple"){
                    //get payment ledgers
                    //insert into payments
                    if($cash !== '0'){
                        $ledger_name = "CASH ACCOUNT";
                        $invs = $get_details->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                        foreach($invs as $inv){
                            $dr_ledger = $inv->acn;
                            $dr_type = $inv->account_group;
                            $dr_group = $inv->sub_group;
                            $dr_class = $inv->class;
                        }
                        //cash flow data
                        $flow_data = array(
                            'account' => $dr_ledger,
                            'destination' => $customer_ledger,
                            'details' => 'Net Income',
                            'trx_number' => $trx_num,
                            'amount' => $cash,
                            'trans_type' => 'inflow',
                            'activity' => 'operating',
                            'post_date' => $date,
                            'posted_by' => $user,
                            'store' => $store
                        );
                        $add_flow = new add_data('cash_flows', $flow_data);
                        $add_flow->create_data();
                        /* add payment to transactions */
                        $cash_debit_data = array(
                            'account' => $dr_ledger,
                            'account_type' => $dr_type,
                            'sub_group' => $dr_group,
                            'class' => $dr_class,
                            'details' => 'Payment for goods sold',
                            'debit' => $cash,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date,
                            'store' => $store
                        );
                        //add debit
                        $add_cash_debit = new add_data('transactions', $cash_debit_data);
                        $add_cash_debit->create_data();   
                        //credit customer account
                        $customer_credit = array(
                            'account' => $customer_ledger,
                            'account_type' => $customer_type,
                            'sub_group' => $sub_group,
                            'class' => $class,
                            'details' => 'Goods purchased',
                            'credit' => $cash,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date,
                            'store' => $store
                        );
                        //add customer credit for wallet payment
                        $add_customer_credit = new add_data('transactions', $customer_credit);
                        $add_customer_credit->create_data();   
                        //insert payment
                        $pay_data = array(
                            'amount_due' => $total_amount,
                            'amount_paid' => $cash,
                            'discount' => $discount,
                            'bank' => $bank,
                            'payment_mode' => 'Cash',
                            'posted_by' => $user,
                            'invoice' => $invoice,
                            'store' => $store,
                            'sales_type' => $type,
                            'customer' => $customer,
                            'post_date' => $date,
                            'trx_number' => $trx_num

                        );
                        $add_payment = new add_data('payments', $pay_data);
                        $add_payment->create_data(); 
                        
                    }
                    if($pos !== '0'){
                        //get bank
                        $bnk = $get_details->fetch_details_group('banks', 'bank', 'bank_id', $bank);
                        $ledger_name = $bnk->bank;
                        $invs = $get_details->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                        foreach($invs as $inv){
                            $dr_ledger = $inv->acn;
                            $dr_type = $inv->account_group;
                            $dr_group = $inv->sub_group;
                            $dr_class = $inv->class;
                        }
                        //cash flow data
                        $flow_data = array(
                            'account' => $dr_ledger,
                            'destination' => $customer_ledger,
                            'details' => 'Net Income',
                            'trx_number' => $trx_num,
                            'amount' => $pos,
                            'trans_type' => 'inflow',
                            'activity' => 'operating',
                            'post_date' => $date,
                            'posted_by' => $user,
                            'store' => $store
                        );
                        $add_flow = new add_data('cash_flows', $flow_data);
                        $add_flow->create_data();
                        /* add payment to transactions */
                        $pos_debit_data = array(
                            'account' => $dr_ledger,
                            'account_type' => $dr_type,
                            'sub_group' => $dr_group,
                            'class' => $dr_class,
                            'details' => 'Payment for goods sold',
                            'debit' => $pos,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date,
                            'store' => $store
                        );
                        //add debit
                        $add_pos_debit = new add_data('transactions', $pos_debit_data);
                        $add_pos_debit->create_data();  
                        //credit customer account
                        $customer_credit = array(
                            'account' => $customer_ledger,
                            'account_type' => $customer_type,
                            'sub_group' => $sub_group,
                            'class' => $class,
                            'details' => 'Goods purchased',
                            'credit' => $pos,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date,
                            'store' => $store
                        );
                        //add customer credit for wallet payment
                        $add_customer_credit = new add_data('transactions', $customer_credit);
                        $add_customer_credit->create_data();    
                        //insert payment
                        $pay_data = array(
                            'amount_due' => $total_amount,
                            'amount_paid' => $pos,
                            'discount' => $discount,
                            'bank' => $bank,
                            'payment_mode' => 'POS',
                            'posted_by' => $user,
                            'invoice' => $invoice,
                            'store' => $store,
                            'sales_type' => $type,
                            'customer' => $customer,
                            'post_date' => $date,
                            'trx_number' => $trx_num

                        );
                        $add_payment = new add_data('payments', $pay_data);
                        $add_payment->create_data(); 
                        
                    }
                    if($transfer !== '0'){
                        //get bank
                        $bnk = $get_details->fetch_details_group('banks', 'bank', 'bank_id', $bank);
                        $ledger_name = $bnk->bank;
                        $invs = $get_details->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                        foreach($invs as $inv){
                            $dr_ledger = $inv->acn;
                            $dr_type = $inv->account_group;
                            $dr_group = $inv->sub_group;
                            $dr_class = $inv->class;
                        }
                        //cash flow data
                        $flow_data = array(
                            'account' => $dr_ledger,
                            'destination' => $customer_ledger,
                            'details' => 'Net Income',
                            'trx_number' => $trx_num,
                            'amount' => $transfer,
                            'trans_type' => 'inflow',
                            'activity' => 'operating',
                            'post_date' => $date,
                            'posted_by' => $user,
                            'store' => $store
                        );
                        $add_flow = new add_data('cash_flows', $flow_data);
                        $add_flow->create_data();
                        /* add payment to transactions */
                        $transfer_debit_data = array(
                            'account' => $dr_ledger,
                            'account_type' => $dr_type,
                            'sub_group' => $dr_group,
                            'class' => $dr_class,
                            'details' => 'Payment for goods sold',
                            'debit' => $transfer,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date,
                            'store' => $store
                        );
                        //add debit
                        $add_transfer_debit = new add_data('transactions', $transfer_debit_data);
                        $add_transfer_debit->create_data();   
                        //credit customer account
                        $customer_credit = array(
                            'account' => $customer_ledger,
                            'account_type' => $customer_type,
                            'sub_group' => $sub_group,
                            'class' => $class,
                            'details' => 'Goods purchased',
                            'credit' => $transfer,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date,
                            'store' => $store
                        );
                        //add customer credit for wallet payment
                        $add_customer_credit = new add_data('transactions', $customer_credit);
                        $add_customer_credit->create_data();   
                        //insert payment
                        $pay_data = array(
                            'amount_due' => $total_amount,
                            'amount_paid' => $transfer,
                            'discount' => $discount,
                            'bank' => $bank,
                            'payment_mode' => 'Transfer',
                            'posted_by' => $user,
                            'invoice' => $invoice,
                            'store' => $store,
                            'sales_type' => $type,
                            'customer' => $customer,
                            'post_date' => $date,
                            'trx_number' => $trx_num
                        );
                        $add_payment = new add_data('payments', $pay_data);
                        $add_payment->create_data(); 
                    }
                    //multiple payment table
                     //insert payment
                     $multi_data = array(
                        'cash' => $cash,
                        'pos' => $pos,
                        'transfer' => $transfer,
                        'bank' => $bank,
                        'posted_by' => $user,
                        'invoice' => $invoice,
                        'store' => $store,
                        'post_date' => $date,
                        'trx_number' => $trx_num
                    );
                    $add_multiple = new add_data('multiple_payments', $multi_data);
                    $add_multiple->create_data(); 
                }elseif($payment_type == "Deposit"){
                    if($contra == "Cash"){
                        $mode = "Cash";
                    }else{
                        $mode = "Transfer";
                    }
                    $pay_data = array(
                        'amount_due' => $total_amount,
                        'amount_paid' => $amount_paid,
                        'discount' => $discount,
                        'bank' => $contra,
                        'payment_mode' => $mode,
                        'posted_by' => $user,
                        'invoice' => $invoice,
                        'store' => $store,
                        'sales_type' => $type,
                        'customer' => $customer,
                        'post_date' => $date,
                        'trx_number' => $trx_num

                    );
                    $add_payment = new add_data('payments', $pay_data);
                    $add_payment->create_data();
                }else{
                    $pay_data = array(
                        'amount_due' => $total_amount,
                        'amount_paid' => $amount_paid,
                        'discount' => $discount,
                        'bank' => $bank,
                        'payment_mode' => $payment_type,
                        'posted_by' => $user,
                        'invoice' => $invoice,
                        'store' => $store,
                        'sales_type' => $type,
                        'customer' => $customer,
                        'post_date' => $date,
                        'trx_number' => $trx_num
                    );
                    $add_payment = new add_data('payments', $pay_data);
                    $add_payment->create_data(); 
                   
                }
                
                if($add_payment){
                
                //check if payment is credit and insert into customer trail and debtors list
                if($payment_type == "Credit"){
                    //insert to customer_trail
                    $cus_data = array(
                        'customer' => $customer,
                        'store' => $store,
                        'description' => 'Credit Sales',
                        'invoice' => $invoice,
                        'amount' => $total_amount,
                        'posted_by' => $user,
                        'post_date' => $date,
                        'trx_number' => $trx_num
                    );
                    $insert_credit = new add_data('customer_trail', $cus_data);
                    $insert_credit->create_data();
                    
                    //insert to debtors list
                    $debt_data = array(
                        'customer' => $customer,
                        'invoice' => $invoice,
                        'amount' => $total_amount,
                        'posted_by' => $user,
                        'store' => $store,
                        'trx_number' => $trx_num,
                        'post_date' => $date
                    );
                    $add_debt = new add_data('debtors', $debt_data);
                    $add_debt->create_data();
                }
                if($payment_type == "Deposit"){
                    //insert to customer_trail
                    $balance_payment = $total_amount - $deposit;
                    $cus_data = array(
                        'customer' => $customer,
                        'store' => $store,
                        'description' => 'Credit Sales',
                        'invoice' => $invoice,
                        'amount' => $balance_payment,
                        'trx_number' => $trx_num,
                        'posted_by' => $user,
                        'post_date' => $date
                    );
                    $insert_credit = new add_data('customer_trail', $cus_data);
                    $insert_credit->create_data();
                   
                    //insert to debtors list
                    $debt_data = array(
                        'customer' => $customer,
                        'invoice' => $invoice,
                        'amount' => $balance_payment,
                        'posted_by' => $user,
                        'store' => $store, 
                        'trx_number' => $trx_num,
                        'post_date' => $date
                    );
                    $add_debt = new add_data('debtors', $debt_data);
                    $add_debt->create_data();
                }
                
?>
<div id="printBtn">
    <button onclick="printSalesReceipt('<?php echo $invoice?>')">Print Receipt <i class="fas fa-print"></i></button>
</div>
<!--  -->
   
<?php
    // echo "<script>window.print();</script>";
                    // }
                }
            }
        
    }else{
        header("Location: ../index.php");
    } 
?>