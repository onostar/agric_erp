<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $receipt = htmlspecialchars(stripslashes($_POST['invoice']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $schedule = htmlspecialchars(stripslashes($_POST['schedule']));
    $bank = htmlspecialchars(stripslashes($_POST['bank']));
    $trans_date = htmlspecialchars(stripslashes($_POST['trans_date']));
    $details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
    $trans_type = "Investment Returns";
    // $type = "Deposit";
    $date = date("Y-m-d H:i:s");
    $company = $_SESSION['company'];
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
        'details' => 'Investment Returns',
        'invoice' => $receipt,
        'store' => $store,
        'bank' => $bank,
        'trx_type' => $trans_type,
        'trans_date' => $trans_date,
        'post_date' => $date,
        'trx_number' => $trx_num,
        'store' => $store
    );
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    require "../PHPMailer/PHPMailerAutoload.php";
    require "../PHPMailer/class.phpmailer.php";
    require "../PHPMailer/class.smtp.php";
    
    //post deposit
    $add_data = new add_data('deposits', $data);
    $add_data->create_data();
    if($add_data){
        //insert into customer trails
        $trail_data = array(
            'customer' => $customer,
            'store' => $store,
            'description' => $trans_type,
            'amount' => $amount,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'post_date' => $date
        );
        $add_trail = new add_data('customer_trail', $trail_data);
        $add_trail->create_data();
        //get schedule details
        $get_details = new selects();
        $results = $get_details->fetch_details_cond('investment_returns', 'schedule_id', $schedule);
        foreach($results as $result){
            $amount_due = $result->amount_due;
            $amount_paid = $result->amount_paid;
            $investment = $result->investment_id;
            $percentage = $result->percentage;
        }
        //get investment details
        $loan_details = $get_details->fetch_details_cond('investments', 'investment_id', $investment);
        foreach($loan_details as $loan){
            $invested_amount = $loan->amount;
            $duration = $loan->duration;
            $currency = $loan->currency;
            $total_naira = $loan->total_in_naira;
            $rate = $loan->exchange_rate;
            // $total_payable = $loan->total_repayment;
        }
       
        //get balance 
        $balance = $amount_due - $amount_paid;
        $new_balance = $balance - $amount;
        if($amount <= $balance){
            $amount_received = $amount;
        }else{
            $amount_received = $balance;
        }
        
        
        $total_paid = $amount_paid + $amount_received;
        if($new_balance <= 0){
            //update repayment schedule
            $update = new Update_table();
            $update->update_double('investment_returns', 'amount_paid', $amount_due, 'payment_status', 1, 'schedule_id', $schedule);
        }else{
            //update repayment schedule
            $update = new Update_table();
            $update->update('investment_returns', 'amount_paid', 'schedule_id', $total_paid, $schedule);
        }
        
        //add into payment table
        $repayment_data = array(
            'customer' => $customer,
            'store' => $store,
            'investment' => $investment,
            'amount' => $amount_received,
            'schedule' => $schedule,
            'payment_mode' => $mode,
            'details' => $details,
            'invoice' => $receipt,
            'bank' => $bank,
            'posted_by' => $user,
            'post_date' => $date,
            'trx_number' => $trx_num,
            'trx_date' => $trans_date
            
        );
        $add_repayment = new add_data('return_payments', $repayment_data);
        $add_repayment->create_data();
        
        //handle excess payment
        /* if($new_balance < 0) {
            $overpaid = -$new_balance;
            $schedules = $get_details->fetch_details_2condOrder('investment_returns', 'payment_status', 'investment_id', 0, $investment, 'due_date');
            if (is_array($schedules)) {
                foreach ($schedules as $next) {
                    if ($overpaid <= 0) break;

                    $next_balance = $next->amount_due - $next->amount_paid;
                    $to_pay = min($overpaid, $next_balance);
                    // Proportional interest and fee for this overpaid portion
                    $next_interest = ($to_pay * $interest_portion) / $total_payable;
                    $next_fee = ($to_pay * $processing_portion) / $total_payable;
                    $new_paid = $next->amount_paid + $to_pay;

                    if($new_paid >= $next->amount_due) {
                        $update->update_double('field_payment_schedule', 'amount_paid', $next->amount_due, 'payment_status', 1, 'repayment_id', $next->repayment_id);
                    }else{
                        $update->update('field_payment_schedule', 'amount_paid', 'repayment_id', $new_paid, $next->repayment_id);
                    }

                    $extra_data = $repayment_data;
                    $extra_data['schedule'] = $next->repayment_id;
                    $extra_data['amount'] = $to_pay;
                    $extra_data['interest'] = $next_interest;
                    $extra_data['processing_fee'] = $next_fee;
                    $extra_data['details'] = 'Excess from previous';
                    (new add_data('field_payments', $extra_data))->create_data();

                    $overpaid -= $to_pay;
                }
            }
            if ($overpaid > 0) {
                $cust = $get_details->fetch_details_cond('customers', 'customer_id', $customer)[0];
                $new_wallet = $cust->wallet_balance + $overpaid;
                $update->update('customers', 'wallet_balance', 'customer_id', $new_wallet, $customer);
            }
        } */
        //accounting entries
        // Accounting entries are done once per total amount paid,
        // not per repayment schedule. Even if the amount affects multiple schedules,
        // we post one consolidated debit and credit here for the full amount.
         // Proportional interest and fee for this payment
       /*  $interest_income = round(($amount * $interest_portion) / $total_payable, 2);
        $processing_fee_income = round(($amount * $processing_portion) / $total_payable, 2); */
        //get customer details
        $bals = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
        foreach($bals as $bal){
            $old_balance = $bal->wallet_balance;
            $ledger = $bal->acn;
            $customer_email = $bal->customer_email;
            $client = $bal->customer;
            // $old_debt = $bal->amount_due;
        };
        //get customer account type
        $types = $get_details->fetch_details_cond('ledgers', 'acn', $ledger);
        foreach($types as $type){
            $ledger_type = $type->account_group;
            $ledger_group = $type->sub_group;
            $ledger_class = $type->class;

        }
        //get contra ledger details
        if($mode == "Cash"){
            $ledger_name = "CASH ACCOUNT";
        }else{
            //get bank
            $get_bank = new selects();
            $bnk = $get_bank->fetch_details_group('banks', 'bank', 'bank_id', $bank);
            $ledger_name = $bnk->bank;
        }
        $invs = $get_details->fetch_details_cond('ledgers', 'ledger', $ledger_name);
        foreach($invs as $inv){
            $dr_ledger = $inv->acn;
            $dr_type = $inv->account_group;
            $dr_group = $inv->sub_group;
            $dr_class = $inv->class;
        }
        //cash or bank
        $credit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'class' => $dr_class,
            'details' => 'Investment Returns',
            'credit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date,
            'store' => $store,

        );
        //customer ledger
        $debit_data = array(
            'account' => $ledger,
            'account_type' => $ledger_type,
            'sub_group' => $ledger_group,
            'class' => $ledger_class,
            'details' => 'Investment Returns',
            'debit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date,
            'store' =>  $store

        );
        //add debit
        $add_debit = new add_data('transactions', $debit_data);
        $add_debit->create_data();      
        //add credit
        $add_credit = new add_data('transactions', $credit_data);
        $add_credit->create_data();
       
        //cash flow data
        $flow_data = array(
            'account' => $dr_ledger,
            'details' => 'Investment Returns',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'outflow',
            'activity' => 'investing',
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
        
        
        $check_repayments = $get_details->fetch_sum_single('investment_returns', 'amount_paid', 'investment_id', $investment);
        foreach($check_repayments as $rep){
            $total_loan_paid = $rep->total;
        }
        $check_dues = $get_details->fetch_sum_single('investment_returns', 'amount_due', 'investment_id', $investment);
        foreach($check_dues as $due){
            $total_loan_due = $due->total;
        }
        $fmt_total_cost = number_format($invested_amount, 2);
       
        $fmt_return = number_format($amount, 2);
       
        if($total_loan_paid === $total_loan_due){
           
            //update investment status
            $update_loan = new Update_table();
            $update_loan->update('investments', 'contract_status', 'investment_id', 2, $investment);
        }
           
        //payment message
         $message = "<p>Dear $client, <br>Your investment return of ₦$fmt_return %($percentage) of your ₦$fmt_total_cost has been paid .<br>
Payment Date: $date.<br><br>
Thank you for investing with Davidorlah Nigeria Ltd.<br>
        Transaction ID: $receipt<br>
       .</p>
       
        <p>Warm regards,<br> 
        $company<br>
        Customer Support";
        //insert into notifications
        $notif_data = array(
            'client' => $customer,
            'subject' => 'Rent Payment Confirmation',
            'message' => 'Dear '.$client.', Your investment return of ₦'.$fmt_return.' has been paid.

Payment Date: '.$date.'

Thank you for investing with Davidorlah Nigeria Ltd',
            'post_date' => $date,
        );
        $add_data = new add_data('notifications', $notif_data);
        $add_data->create_data();
        /* send mails to customer */
        function smtpmailer($to, $from, $from_name, $subject, $body){
            $mail = new PHPMailer();
            $mail->IsSMTP();
            $mail->SMTPAuth = true; 
    
            $mail->SMTPSecure = 'ssl'; 
            $mail->Host = 'www.dorthprosuite.com';
            $mail->Port = 465; 
            $mail->Username = 'admin@dorthprosuite.com';
            $mail->Password = 'yMcmb@her0123!';   
    
    
            $mail->IsHTML(true);
            $mail->From="admin@dorthprosuite.com";
            $mail->FromName=$from_name;
            $mail->Sender=$from;
            $mail->AddReplyTo($from, $from_name);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($to);
            $mail->AddAddress('onostarmedia@gmail.com');
            
            if(!$mail->Send())
            {
                $error = "Failed to send mail";
                
                return $error; 
            }
            else 
            {
                
                /* success message */
                
                $error = "Message Sent Successfully";
                
                // header("Location: index.html");
                return $error;
            }
        }
        
        $to = $customer_email;
        $from = 'admin@dorthprosuite.com';
        $from_name = "$company";
        $name = "$company";
        $subj = 'Investment Return Payment Confirmation';
        $msg = "<div>$message</div>";
        
        $error=smtpmailer($to, $from, $name ,$subj, $msg);
        
?>
    <!-- <div id="printBtn">
        <button onclick="printPaymentReceipt('<?php echo $receipt?>')">Print Receipt <i class="fas fa-print"></i></button>
    </div> -->
<?php

        echo "<p style='color:green; margin:5px 50px'>Payment posted successfully!</p>";
    // }
}