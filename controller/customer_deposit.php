<?php

    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $receipt = htmlspecialchars(stripslashes($_POST['invoice']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
    $amount = htmlspecialchars(stripslashes(($_POST['amount'])));
    $bank = htmlspecialchars(stripslashes(($_POST['bank'])));
    $trans_date = htmlspecialchars(stripslashes(($_POST['trans_date'])));
    $details = ucwords(htmlspecialchars(stripslashes(($_POST['details']))));
    $trans_type = "Deposit";
    $type = "Deposit";
    $date = date("Y-m-d H:i:s");
    //generate transaction number
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    $trx_num = "TR".$ran_num.$todays_date;
    $data = array(
        'posted_by' => $user,
        'customer' => $customer,
        'payment_mode' => $mode,
        'amount' => $amount,
        'details' => $details,
        'invoice' => $receipt,
        'store' => $store,
        'bank' => $bank,
        'trans_date' => $trans_date,
        'post_date' => $date,
        'trx_number' => $trx_num
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    
    
    //post deposit
    $add_data = new add_data('deposits', $data);
    $add_data->create_data();
    if($add_data){
        //insert into customer trails
        $insert_trail = new customer_trail($customer, $store, $trans_type, $amount, $user, $trx_num);
        $insert_trail->add_trail();
        //get customer details
        $get_balance = new selects();
        $bals = $get_balance->fetch_details_cond('customers', 'customer_id', $customer);
        foreach($bals as $bal){
            $old_balance = $bal->wallet_balance;
            $ledger = $bal->acn;
            // $old_debt = $bal->amount_due;
        };
         //get customer account type
        $get_type = new selects();
        $types = $get_type->fetch_details_cond('ledgers', 'acn', $ledger);
        foreach($types as $type){
            $ledger_type = $type->account_group;
            $ledger_group = $type->sub_group;

        }
        //update all balances
        //check if customer was owing
        /* if($old_debt > 0){
            //remove deposited money from debt
            $new_debt = $old_debt - $amount;
            if($new_debt < 0){
                $new_amount = intval($new_debt) * (-1);
                //add excess amount to wallet balance
                $new_wallet = $new_amount + $old_balance;
                //update wallet and debt balance
                $update_wallet = new Update_table();
                $update_wallet->update_double('customers', 'wallet_balance', $new_wallet, 'amount_due', 0, 'customer_id', $customer);
                
            }else{
                //update debt balance
                $new_amount = $new_debt;
                $update_debt = new Update_table();
                $update_debt->update('customers', 'amount_due', 'customer_id', $new_amount, $customer);
            }
        }else{
            //add funds to wallet
            $new_wallet = $old_balance + $amount;
            $update_balance = new Update_table();
            $update_balance->update('customers', 'wallet_balance', 'customer_id', $new_wallet, $customer);
        } */
        

       //add funds to wallet
       $new_wallet = intval($old_balance) + intval($amount);
       $update_balance = new Update_table();
       $update_balance->update('customers', 'wallet_balance', 'customer_id', $new_wallet, $customer);
        // if($update_wallet){
        //insert into transaction table
            //get ledgers
        if($mode == "Cash"){
            $ledger_name = "CASH ACCOUNT";
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
        }
        $debit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'details' => $details,
            'debit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date

        );
        $credit_data = array(
            'account' => $ledger,
            'account_type' => $ledger_type,
            'sub_group' => $ledger_group,
            'details' => $details,
            'credit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date
        );
        //add debit
        $add_debit = new add_data('transactions', $debit_data);
        $add_debit->create_data();      
        //add credit
        $add_credit = new add_data('transactions', $credit_data);
        $add_credit->create_data();
         //cash flow date
         $flow_data = array(
            'account' => $dr_ledger,
            'details' => 'Net Income',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'inflow',
            'activity' => 'operating',
            'post_date' => $date,
            'posted_by' => $user
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
?>
    <div id="printBtn">
        <button onclick="showPage('../controller/customer_statement.php?customer=<?php echo $customer?>')">Continue <i class="fas fa-angle-double-right"></i></button>
    </div>
<?php

        echo "<p style='color:green; margin:5px 50px'>Payment posted successfully!</p>";
    // }
}