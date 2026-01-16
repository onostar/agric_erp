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
    $trans_type = "Loan Repayment";
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
        'details' => $details,
        'invoice' => $receipt,
        'store' => $store,
        'bank' => $bank,
        'trx_type' => $trans_type,
        'trans_date' => $trans_date,
        'post_date' => $date,
        'trx_number' => $trx_num,
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
        $results = $get_details->fetch_details_cond('rent_schedule', 'repayment_id', $schedule);
        foreach($results as $result){
            $amount_due = $result->amount_due;
            $amount_paid = $result->amount_paid;
            $loan_id = $result->assigned_id;
        }
        //get loan details
        $loan_details = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $loan_id);
        foreach($loan_details as $loan){
            $annual_rent = $loan->annual_rent;
            $field = $loan->field;
            // $total_payable = $loan->total_repayment;
        }
        //get field name
        $field_details = $get_details->fetch_details_cond('fields', 'field_id', $field);
        foreach($field_details as $field_detail){
            $field_name = $field_detail->field_name;
        }
        //get balance 
        $balance = $amount_due - $amount_paid;
        $new_balance = $balance - $amount;
        if($amount <= $balance){
            $amount_received = $amount;
        }else{
            $amount_received = $balance;
        }
        // Calculate total interest and processing fee based on loan
        /* $interest_portion = ($loan_amount * $interest_rate) / 100;
        $processing_portion = ($loan_amount * $processing_rate) / 100; */

        // Proportional interest and fee for this payment
       /*  $interest = ($amount_received * $interest_portion) / $total_payable;
        $processing_fee = ($amount_received * $processing_portion) / $total_payable;
        $principal = $amount_received - $interest - $processing_fee; */
        
        $total_paid = $amount_paid + $amount_received;
        if($new_balance <= 0){
            //update repayment schedule
            $update = new Update_table();
            $update->update_double('rent_schedule', 'amount_paid', $amount_due, 'payment_status', 1, 'repayment_id', $schedule);
        }else{
            //update repayment schedule
            $update = new Update_table();
            $update->update('rent_schedule', 'amount_paid', 'repayment_id', $total_paid, $schedule);
        }
        
        //add into repayment table
        $repayment_data = array(
            'customer' => $customer,
            'store' => $store,
            'loan' => $loan_id,
            'amount' => $amount_received,
            'schedule' => $schedule,
            /* 'interest' => $interest,
            'processing_fee' => $processing_fee, */
            'payment_mode' => $mode,
            'details' => $details,
            'invoice' => $receipt,
            'bank' => $bank,
            'trx_date' => $trans_date,
            'posted_by' => $user,
            'post_date' => $date,
            'trx_number' => $trx_num,
            
        );
        $add_repayment = new add_data('rent_payments', $repayment_data);
        $add_repayment->create_data();
        //handle excess payment
        if($new_balance < 0) {
            $overpaid = -$new_balance;
            $schedules = $get_details->fetch_details_2condOrder('rent_schedule', 'payment_status', 'assigned_id', 0, $loan_id, 'due_date');
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
                        $update->update_double('rent_schedule', 'amount_paid', $next->amount_due, 'payment_status', 1, 'repayment_id', $next->repayment_id);
                    }else{
                        $update->update('rent_schedule', 'amount_paid', 'repayment_id', $new_paid, $next->repayment_id);
                    }

                    $extra_data = $repayment_data;
                    $extra_data['schedule'] = $next->repayment_id;
                    $extra_data['amount'] = $to_pay;
                    $extra_data['interest'] = $next_interest;
                    $extra_data['processing_fee'] = $next_fee;
                    $extra_data['details'] = 'Excess from previous';
                    (new add_data('rent_payments', $extra_data))->create_data();

                    $overpaid -= $to_pay;
                }
            }
            if ($overpaid > 0) {
                $cust = $get_details->fetch_details_cond('customers', 'customer_id', $customer)[0];
                $new_wallet = $cust->wallet_balance + $overpaid;
                $update->update('customers', 'wallet_balance', 'customer_id', $new_wallet, $customer);
            }
        }
        //accounting entries
        // Accounting entries are done once per total amount paid,
        // not per repayment schedule. Even if the amount affects multiple schedules,
        // we post one consolidated debit and credit here for the full amount.
         // Proportional interest and fee for this payment
       /*  $interest_income = round(($amount * $interest_portion) / $total_payable, 2);
        $processing_fee_income = round(($amount * $processing_portion) / $total_payable, 2); */
        //get customer details
        $get_balance = new selects();
        $bals = $get_balance->fetch_details_cond('customers', 'customer_id', $customer);
        foreach($bals as $bal){
            $old_balance = $bal->wallet_balance;
            $ledger = $bal->acn;
            $customer_email = $bal->customer_email;
            $client = $bal->customer;
            // $old_debt = $bal->amount_due;
        };
        //get customer account type
        $get_type = new selects();
        $types = $get_type->fetch_details_cond('ledgers', 'acn', $ledger);
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
        $get_inv = new selects();
        $invs = $get_inv->fetch_details_cond('ledgers', 'ledger', $ledger_name);
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
            'details' => 'Rent payment',
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
            'details' => 'Rent payment',
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
       
        //cash flow date
        $flow_data = array(
            'account' => $dr_ledger,
            'details' => 'Loan Repayment',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'outflow',
            'activity' => 'operating',
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
        
        //check if all repayments have been paid and update loan status
        $check_repayments = $get_details->fetch_sum_single('rent_schedule', 'amount_paid', 'assigned_id', $loan_id);
        foreach($check_repayments as $rep){
            $total_loan_paid = $rep->total;
        }
        $check_dues = $get_details->fetch_sum_single('rent_schedule', 'amount_due', 'assigned_id', $loan_id);
        foreach($check_dues as $due){
            $total_loan_due = $due->total;
        }
        if($total_loan_paid === $total_loan_due){
            //update loan status
            $update_loan = new Update_table();
            $update_loan->update('assigned_fields', 'contract_status', 'loan_id', 3, $loan_id);
        }
        
        $amount = number_format($amount, 2);
        $trx_date = date("jS F Y, h:ia", strtotime($date));
        $message = "<p>Dear $client, <br>Your annual rent return of ₦$annual_rent has been paid for your field ($field_name).<br>
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
            'message' => 'Dear '.$client.', Your annual rent return of ₦'.$annual_rent.' has been paid for your field ('.$field_name.').

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
        $subj = 'Rent Payment Confirmation';
        $msg = "<div>$message</div>";
        
        $error=smtpmailer($to, $from, $name ,$subj, $msg);
        
?>
    <!-- <div id="printBtn">
        <button onclick="printDepositReceipt('<?php echo $receipt?>')">Print Receipt <i class="fas fa-print"></i></button>
    </div> -->
<?php

        echo "<div class='success'><p style=' margin:5px 50px'>Payment posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    // }
}