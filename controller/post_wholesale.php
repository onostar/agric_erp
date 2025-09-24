<?php
// session_start();
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
        $invoice = $_POST['sales_invoice'];
        $payment_type = htmlspecialchars(stripslashes($_POST['payment_type']));
        $bank = htmlspecialchars(stripslashes($_POST['bank']));
        $cash = htmlspecialchars(stripslashes($_POST['multi_cash']));
        $pos = htmlspecialchars(stripslashes($_POST['multi_pos']));
        $transfer = htmlspecialchars(stripslashes($_POST['multi_transfer']));
        $discount = htmlspecialchars(stripslashes($_POST['discount']));
        $store = htmlspecialchars(stripslashes($_POST['store']));
        $type = "Wholesale";
        $wallet = htmlspecialchars(stripslashes($_POST['wallet']));
        $deposit = htmlspecialchars(stripslashes($_POST['deposit']));
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
            $get_amount_due = new selects();
            $amts = $get_amount_due->fetch_details_cond('customers', 'customer_id', $customer);
            foreach($amts as $amt){
                // $amount_due = $amt->amount_due;
                // $wallet = $amt->wallet_balance;
                $customer_ledger = $amt->acn;

            }
            
            //get customer leger type
            $get_cl = new selects();
            $cusl = $get_cl->fetch_details_cond('ledgers', 'acn', $customer_ledger);
            foreach($cusl as $cus){
                $customer_type = $cus->account_group;
                $sub_group = $cus->sub_group;
                $class = $cus->class;
            }
            //insert into audit trail
            //get items and quantity sold in the invoice
            $get_item = new selects();
            $items = $get_item->fetch_details_cond('sales', 'invoice', $invoice);
            foreach($items as $item){
                $all_item = $item->item;
                $sold_qty = $item->quantity;
                //get item previous quantity in inventory
                $get_qty = new selects();
                $prev_qtys = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $all_item);
                foreach($prev_qtys as $prev_qty){    
                    //insert into audit trail
                    $inser_trail = new audit_trail($all_item, $trans_type, $prev_qty->quantity, $sold_qty, $user, $store, $date);
                    $inser_trail->audit_trail();
                
                }
            }
            

        //update all items with this invoice
        $update_invoice = new Update_table();
        $update_invoice->update_double('sales', 'sales_status', 2, 'post_date', $date, 'invoice', $invoice);
        //update quantity of the items in inventory
        //get all items first in the invoice
        $get_items = new selects();
        $rows = $get_items->fetch_details_cond('sales', 'invoice', $invoice);
        
        foreach($rows as $row){
            //update individual quantity in inventory
            $update_qty = new Update_table();
            $update_qty->update_inv_qty($row->quantity, $row->item, $store);
            
        }
        
            if($update_invoice){
                //insert into transaction table     

                //insert payment details into payment table
                //get invoice total amount
                $get_inv_total = new selects();
                $results = $get_inv_total->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                foreach($results as $result){
                    $inv_amount = $result->total;
                }
                //get amount paid
                if($payment_type == "Credit"){
                    $amount_paid = 0;
                    /* $new_wallet = $wallet - $inv_amount; */
                }elseif($payment_type == "Deposit"){
                    $amount_paid = $deposit;
                    /* $new_wallet = $wallet + ($deposit - $inv_amount); */

                }else{
                    $amount_paid = $inv_amount - $discount;
                    // $new_wallet = $wallet;

                }
                //get income legder id
                $get_income = new selects();
                $incs = $get_income->fetch_details_cond('ledgers', 'ledger', 'GENERAL REVENUE');
                foreach($incs as $inc){
                    $income_ledger = $inc->acn;
                    $income_type = $inc->account_group;
                    $income_group = $inc->sub_group;
                    $income_class = $inc->class;
                }
                $debit_data = array(
                    'account' => $customer_ledger,
                    'account_type' => $customer_type,
                    'sub_group' => $sub_group,
                    'class' => $class,
                    'details' => 'Customer Invoice',
                    'debit' => $inv_amount,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trx_date

                );
                $credit_data = array(
                    'account' => $income_ledger,
                    'account_type' => $income_type,
                    'sub_group' => $income_group,
                    'class' => $income_class,
                    'details' => 'Customer Invoice',
                    'credit' => $inv_amount,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'trans_date' => $trx_date

                );
                //add debit
                $add_debit = new add_data('transactions', $debit_data);
                $add_debit->create_data();      
                //add credit
                $add_credit = new add_data('transactions', $credit_data);
                $add_credit->create_data();
                /* cash flow */
                if($payment_type !== "Credit" && $payment_type !== "Multiple"){
                    //get payment ledgers
                    if($payment_type == "Cash"){
                        $ledger_name = "CASH ACCOUNT";
                    }elseif($payment_type == "Deposit"){
                        if($contra == "Cash"){
                            $ledger_name = "CASH ACCOUNT";
                        }else{
                            //get bank
                            $get_bank = new selects();
                            $bnk = $get_bank->fetch_details_group('banks', 'bank', 'bank_id', $contra);
                            $ledger_name = $bnk->bank;
                        }
                    }else{
                        //get bank
                        $get_bank = new selects();
                        $bnk = $get_bank->fetch_details_group('banks', 'bank', 'bank_id', $bank);
                        $ledger_name = $bnk->bank;
                    }
                    $get_inv = new selects();
                    $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                    foreach($invs as $inv){
                        $dr_ledger = $inv->acn;
                        $dr_type = $inv->account_group;
                        $dr_group = $inv->sub_group;
                        $dr_class = $inv->class;
                    }
                    //cash flow data
                    $flow_data = array(
                        'account' => $dr_ledger,
                        'details' => 'Net Income',
                        'trx_number' => $trx_num,
                        'amount' => $amount_paid,
                        'trans_type' => 'inflow',
                        'activity' => 'operating',
                        'post_date' => $date,
                        'posted_by' => $user
                    );
                    $add_flow = new add_data('cash_flows', $flow_data);
                    $add_flow->create_data();
                    /* add payment to transactions */
                    
                    $debit_data = array(
                        'account' => $dr_ledger,
                        'account_type' => $dr_type,
                        'sub_group' => $dr_group,
                        'class' => $dr_class,
                        'details' => 'Payment for goods sold',
                        'debit' => $amount_paid,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date
            
                    );
                    $credit_data = array(
                        'account' => $customer_ledger,
                        'account_type' => $customer_type,
                        'sub_group' => $sub_group,
                        'class' => $class,
                        'details' => 'Payment for goods sold',
                        'credit' => $amount_paid,
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date
            
                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data();
                }
                /* cost of sales */
                $get_cost= new selects();
                $coss = $get_cost->fetch_sum_single('sales', 'cost', 'invoice', $invoice);
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
                $add_data->create_data();

                //insert into transaction table
            $debit_data = array(
                'account' => $cos_ledger,
                'account_type' => $cos_type,
                'sub_group' => $cos_group,
                'class' => $cos_class,
                'debit' => $total_cost,
                'details' => 'Cost of sales',
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'trans_date' => $trx_date

            );
            $credit_data = array(
                'account' => $inv_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_group,
                'class' => $inv_class,
                'credit' => $total_cost,
                'details' => 'Cost of sales',
                'post_date' => $date,
                'posted_by' => $user,
                'trx_number' => $trx_num,
                'trans_date' => $trx_date

            );
            //add debit
            $add_debit = new add_data('transactions', $debit_data);
            $add_debit->create_data();      
            //add credit
            $add_credit = new add_data('transactions', $credit_data);
            $add_credit->create_data(); 
            //update invoice with trxnumber
            $update_invoice = new Update_table();
            $update_invoice->update('sales', 'trx_number', 'invoice', $trx_num, $invoice);
            
            
                //insert payments
                if($payment_type == "Multiple"){
                    //get payment ledgers
                    //insert into payments
                    if($cash !== '0'){
                        $ledger_name = "CASH ACCOUNT";
                        $get_inv = new selects();
                        $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                        foreach($invs as $inv){
                            $dr_ledger = $inv->acn;
                            $dr_type = $inv->account_group;
                            $dr_group = $inv->sub_group;
                            $dr_class = $inv->class;
                        }
                        //cash flow data
                        $flow_data = array(
                            'account' => $dr_ledger,
                            'details' => 'Net Income',
                            'trx_number' => $trx_num,
                            'amount' => $cash,
                            'trans_type' => 'inflow',
                            'activity' => 'operating',
                            'post_date' => $date,
                            'posted_by' => $user
                        );
                        $add_flow = new add_data('cash_flows', $flow_data);
                        $add_flow->create_data();
                        /* add payment to transactions */
                        
                        $debit_data = array(
                            'account' => $dr_ledger,
                            'account_type' => $dr_type,
                            'sub_group' => $dr_group,
                            'class' => $dr_class,
                            'details' => 'Payment for goods sold',
                            'debit' => $cash,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date
                
                        );
                        $credit_data = array(
                            'account' => $customer_ledger,
                            'account_type' => $customer_type,
                            'sub_group' => $sub_group,
                            'class' => $class,
                            'details' => 'Payment for goods sold',
                            'credit' => $cash,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date
                
                        );
                        //add debit
                        $add_debit = new add_data('transactions', $debit_data);
                        $add_debit->create_data();      
                        //add credit
                        $add_credit = new add_data('transactions', $credit_data);
                        $add_credit->create_data();
                        //insert payment
                        $pay_data = array(
                            'amount_due' => $inv_amount,
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
                        $get_bank = new selects();
                        $bnk = $get_bank->fetch_details_group('banks', 'bank', 'bank_id', $bank);
                        $ledger_name = $bnk->bank;
                        $get_inv = new selects();
                        $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                        foreach($invs as $inv){
                            $dr_ledger = $inv->acn;
                            $dr_type = $inv->account_group;
                            $dr_group = $inv->sub_group;
                            $dr_class = $inv->class;
                        }
                        //cash flow data
                        $flow_data = array(
                            'account' => $dr_ledger,
                            'details' => 'Net Income',
                            'trx_number' => $trx_num,
                            'amount' => $pos,
                            'trans_type' => 'inflow',
                            'activity' => 'operating',
                            'post_date' => $date,
                            'posted_by' => $user
                        );
                        $add_flow = new add_data('cash_flows', $flow_data);
                        $add_flow->create_data();
                        /* add payment to transactions */
                        
                        $debit_data = array(
                            'account' => $dr_ledger,
                            'account_type' => $dr_type,
                            'sub_group' => $dr_group,
                            'class' => $dr_class,
                            'details' => 'Payment for goods sold',
                            'debit' => $pos,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date
                
                        );
                        $credit_data = array(
                            'account' => $customer_ledger,
                            'account_type' => $customer_type,
                            'sub_group' => $sub_group,
                            'class' => $class,
                            'details' => 'Payment for goods sold',
                            'credit' => $pos,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date
                
                        );
                        //add debit
                        $add_debit = new add_data('transactions', $debit_data);
                        $add_debit->create_data();      
                        //add credit
                        $add_credit = new add_data('transactions', $credit_data);
                        $add_credit->create_data();
                        //insert payment
                        $pay_data = array(
                            'amount_due' => $inv_amount,
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
                        $get_bank = new selects();
                        $bnk = $get_bank->fetch_details_group('banks', 'bank', 'bank_id', $bank);
                        $ledger_name = $bnk->bank;
                        $get_inv = new selects();
                        $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', $ledger_name);
                        foreach($invs as $inv){
                            $dr_ledger = $inv->acn;
                            $dr_type = $inv->account_group;
                            $dr_group = $inv->sub_group;
                            $dr_class = $inv->class;
                        }
                        //cash flow data
                        $flow_data = array(
                            'account' => $dr_ledger,
                            'details' => 'Net Income',
                            'trx_number' => $trx_num,
                            'amount' => $transfer,
                            'trans_type' => 'inflow',
                            'activity' => 'operating',
                            'post_date' => $date,
                            'posted_by' => $user
                        );
                        $add_flow = new add_data('cash_flows', $flow_data);
                        $add_flow->create_data();
                        /* add payment to transactions */
                        
                        $debit_data = array(
                            'account' => $dr_ledger,
                            'account_type' => $dr_type,
                            'sub_group' => $dr_group,
                            'class' => $dr_class,
                            'details' => 'Payment for goods sold',
                            'debit' => $transfer,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date
                
                        );
                        $credit_data = array(
                            'account' => $customer_ledger,
                            'account_type' => $customer_type,
                            'sub_group' => $sub_group,
                            'class' => $class,
                            'details' => 'Payment for goods sold',
                            'credit' => $transfer,
                            'post_date' => $date,
                            'posted_by' => $user,
                            'trx_number' => $trx_num,
                            'trans_date' => $trx_date
                
                        );
                        //add debit
                        $add_debit = new add_data('transactions', $debit_data);
                        $add_debit->create_data();      
                        //add credit
                        $add_credit = new add_data('transactions', $credit_data);
                        $add_credit->create_data();
                        //insert payment
                        $pay_data = array(
                            'amount_due' => $inv_amount,
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
                    /* $insert_multi = new multiple_payment($user, $invoice, $cash, $pos, $transfer, $bank, $store, $date, $trx_num);
                    $insert_multi->multi_pay(); */
                }elseif($payment_type == "Deposit"){
                    if($contra == "Cash"){
                        $mode = "Cash";
                    }else{
                        $mode = "Transfer";
                    }
                    $pay_data = array(
                        'amount_due' => $inv_amount,
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
                        'amount_due' => $inv_amount,
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
                // if($payment_type == "Wallet"){
                    //update wallet balance
                    // $new_balance = $wallet - $amount_paid;
                    /* $update_wallet = new Update_table();
                    $update_wallet->update('customers', 'wallet_balance', 'customer_id', $new_wallet, $customer); */
                //  }
                if($add_payment){
                
                //check if payment is credit and insert into customer trail and debtors list
                if($payment_type == "Credit"){
                    //insert to customer_trail
                    $insert_credit = new customer_trail($customer, $store, 'Credit sales', $inv_amount, $user);
                    $insert_credit->add_trail();
                    //insert to debtors list
                    $debt_data = array(
                        'customer' => $customer,
                        'invoice' => $invoice,
                        'amount' => $inv_amount,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'store' => $store
                    );
                    $add_debt = new add_data('debtors', $debt_data);
                    $add_debt->create_data();
                    
                }
                if($payment_type == "Deposit"){
                    //insert to customer_trail
                    $balance_payment = $inv_amount - $deposit;
                    $insert_credit = new customer_trail($customer, $store, 'Credit sales', $balance_payment, $user);
                    $insert_credit->add_trail();
                    //insert to debtors list
                    $debt_data = array(
                        'customer' => $customer,
                        'invoice' => $invoice,
                        'amount' => $balance_payment,
                        'trx_number' => $trx_num,
                        'posted_by' => $user,
                        'store' => $store
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