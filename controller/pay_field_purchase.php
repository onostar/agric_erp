<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $receipt = htmlspecialchars(stripslashes($_POST['invoice']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
    $payment_id = htmlspecialchars(stripslashes($_POST['payment_id']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $schedule = htmlspecialchars(stripslashes($_POST['schedule']));
    $bank = htmlspecialchars(stripslashes($_POST['bank']));
    $trans_date = htmlspecialchars(stripslashes($_POST['trans_date']));
    $details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
    $trans_type = "Field Purchase Payment";
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
        'details' => 'Field purchase payment',
        'invoice' => $receipt,
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
        $results = $get_details->fetch_details_cond('field_payment_schedule', 'repayment_id', $schedule);
        foreach($results as $result){
            $amount_due = $result->amount_due;
            $amount_paid = $result->amount_paid;
            $loan_id = $result->assigned_id;
        }
        //get loan details
        $loan_details = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $loan_id);
        foreach($loan_details as $loan){
            $purchase_cost = $loan->total_due;
            $duration = $loan->contract_duration;
            $annual_rent = $loan->annual_rent;
            $field_id = $loan->field;
            $size = $loan->field_size;
            $rent_percentage = $loan->rent_percentage;
            // $total_payable = $loan->total_repayment;
        }
        //getfield details
        $fields = $get_details->fetch_details_cond('fields', 'field_id', $field_id);
        foreach($fields as $fd){
            $field_name = $fd->field_name;
            // $size = $fd->field_size;
            $location = $fd->location;
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
        $update = new Update_table();
        if($new_balance <= 0){
            //update repayment schedule
            $update->update_double('field_payment_schedule', 'amount_paid', $amount_due, 'payment_status', 1, 'repayment_id', $schedule);
        }else{
            //update repayment schedule
            $update->update('field_payment_schedule', 'amount_paid', 'repayment_id', $total_paid, $schedule);
        }
        //check if there has been payment before
        $check = $get_details->fetch_count_cond('field_payments', 'loan', $loan_id);
        if($check == 0){
            //now generate rent payment schedule for the field
            $installments = $duration; //yearly
            
            $start_date = new DateTime($date);
            for($i = 1; $i <= $installments; $i++){
                $due_date = clone $start_date;$due_date->modify("+$i year");
                
                $rent_data = array(
                    'field' => $field_id,
                    'assigned_id' => $loan_id,
                    'customer' => $customer,
                    'due_date' => $due_date->format('Y-m-d'),
                    'amount_due' => $annual_rent,
                    'store' => $store,
                    'posted_by' => $user,
                    'post_date' => $date
                );

                $insert_repayment = new add_data('rent_schedule', $rent_data);
                $insert_repayment->create_data();
               
            }
        }
        //add into payment table
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
            'posted_by' => $user,
            'post_date' => $date,
            'trx_number' => $trx_num,
            'trx_date' => $trans_date
            
        );
        $add_repayment = new add_data('field_payments', $repayment_data);
        $add_repayment->create_data();
        //check if payment iscoming through payment evidence submitted
        if($payment_id != 0){
            //update payment evidence status to approved
            $update_evidence = new Update_table();
            $update_evidence->update_double('payment_evidence', 'payment_status', 1, 'trx_number', $trx_num, 'payment_id', $payment_id);
        }
        //handle excess payment
        if($new_balance < 0) {
            $overpaid = -$new_balance;
            $schedules = $get_details->fetch_details_2condOrder('field_payment_schedule', 'payment_status', 'assigned_id', 0, $loan_id, 'due_date');
            if (is_array($schedules)) {
                foreach ($schedules as $next) {
                    if ($overpaid <= 0) break;

                    $next_balance = $next->amount_due - $next->amount_paid;
                    $to_pay = min($overpaid, $next_balance);
                    // Proportional interest and fee for this overpaid portion
                    /* $next_interest = ($to_pay * $interest_portion) / $total_payable;
                    $next_fee = ($to_pay * $processing_portion) / $total_payable; */
                    $new_paid = $next->amount_paid + $to_pay;

                    if($new_paid >= $next->amount_due) {
                        $update->update_double('field_payment_schedule', 'amount_paid', $next->amount_due, 'payment_status', 1, 'repayment_id', $next->repayment_id);
                    }else{
                        $update->update('field_payment_schedule', 'amount_paid', 'repayment_id', $new_paid, $next->repayment_id);
                    }

                    $extra_data = $repayment_data;
                    $extra_data['schedule'] = $next->repayment_id;
                    $extra_data['amount'] = $to_pay;
                    /* $extra_data['interest'] = $next_interest;
                    $extra_data['processing_fee'] = $next_fee; */
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
        $debit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'class' => $dr_class,
            'details' => 'Field Purchase payment',
            'debit' => $amount,
            'post_date' => $date,
            'posted_by' => $user,
            'trx_number' => $trx_num,
            'trans_date' => $trans_date,
            'store' => $store,

        );
        //customer ledger
        $credit_data = array(
            'account' => $ledger,
            'account_type' => $ledger_type,
            'sub_group' => $ledger_group,
            'class' => $ledger_class,
            'details' => 'Field Purchase Payment',
            'credit' => $amount,
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
            'details' => 'Field Purchase Payment',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'inflow',
            'activity' => 'investing',
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
        
        
        $check_repayments = $get_details->fetch_sum_single('field_payment_schedule', 'amount_paid', 'assigned_id', $loan_id);
        foreach($check_repayments as $rep){
            $total_loan_paid = $rep->total;
        }
        $check_dues = $get_details->fetch_sum_single('field_payment_schedule', 'amount_due', 'assigned_id', $loan_id);
        foreach($check_dues as $due){
            $total_loan_due = $due->total;
        }
        $fmt_total_cost = number_format($purchase_cost, 2);
        $fmt_total_paid = number_format($total_paid, 2);
        $fmt_rent = number_format($annual_rent, 2);
        $fmt_remaining = number_format($purchase_cost - $total_paid, 2);
         $sqm = $size * 500;
        if($total_loan_paid === $total_loan_due){
           
            //update loan status
            $update_loan = new Update_table();
            $update_loan->update('assigned_fields', 'contract_status', 'assigned_id', 2, $loan_id);
            //build rent schedule text
            $rent_schedule_text = "<ul>";
            //fetch investment returns details for email
            $rent_returns = $get_details->fetch_details_cond('rent_schedule', 'assigned_id', $loan_id);
            foreach($rent_returns as $ir){
                $due = date("jS F, Y", strtotime($ir->due_date));
                $annual_rent = number_format($ir->amount_due, 2);
               $rent_schedule_text .= "<li>₦" . $annual_rent . " due on " . $due . "</li>";
            }
            $rent_schedule_text .= "</ul>";

            // build email message for full payment
            $email_message = "
            <p>Dear $client,</p>
            <p>Congratulations! You have successfully completed your field purchase payment.</p>
            <h3>Purchase Summary:</h3>
            <ul>
                <li><strong>Field:</strong> $field_name</li>
                <li><strong>Location:</strong> $location</li>
                <li><strong>Size:</strong> $size Plot ($sqm m²)</li>
                <li><strong>Contract Duration:</strong> $duration year(s)</li>
                <li><strong>Total Purchase Cost:</strong> ₦$fmt_total_cost</li>
                <li><strong>Total Paid:</strong> ₦$fmt_total_paid</li>
                <li><strong>Amount Remaining:</strong> ₦0.00 (Fully Paid)</li>
                <li><strong>Annual Rent:</strong> ₦$fmt_rent</li>
                <li><strong>Rent Percentage:</strong> $rent_percentage%</li>
            </ul>
            <p>Your annual rent returns will now commence according to the following schedule:</p>
            $rent_schedule_text
            <p>Thank you for your trust in <strong>Davidorlah Nigeria Limited</strong>. We look forward to building a fruitful partnership with you.</p>
            <br><p>Warm regards,<br><strong>Farm Management Team</strong><br>Davidorlah Nigeria Limited</p>";

            // insert notification
            $notif_data = array(
                'client' => $customer,
                'subject' => 'Field Purchase Completed - Annual Rent Activated',
                'message' => 'Dear ' . $client . ', your field (' . $field_name . ' - ' . $size . ' plot(' . $sqm . ' m²)) has been fully paid. Your annual rent of ₦' . $fmt_rent . ' will commence now for the next ' . $duration . ' year(s).',
                'post_date' => $date,
            );

            $add_data = new add_data('notifications', $notif_data);
            $add_data->create_data();
        } else {
            // partial payment message
           
            $email_message = "
            <p>Dear $client,</p>
            <p>Your recent payment has been received successfully for your field purchase.</p>
            <h3>Purchase Summary:</h3>
            <ul>
                <li><strong>Field:</strong> $field_name</li>
                <li><strong>Location:</strong> $location</li>
                <li><strong>Size:</strong> $size Plot ($sqm m²)</li>
                <li><strong>Contract Duration:</strong> $duration year(s)</li>
                <li><strong>Total Purchase Cost:</strong> ₦$fmt_total_cost</li>
                <li><strong>Total Paid So Far:</strong> ₦$fmt_total_paid</li>
                <li><strong>Amount Remaining:</strong> ₦$fmt_remaining</li>
                <li><strong>Annual Rent:</strong> ₦$fmt_rent</li>
                <li><strong>Rent Percentage:</strong> $rent_percentage%</li>
            </ul>
            <p>Once your installment payments are completed, your annual rent returns will start immediately.</p>
            <p>Thank you for your continued commitment.</p>
            <br><p>Warm regards,<br><strong>Farm Management Team</strong><br>Davidorlah Nigeria Limited</p>";

            // partial payment notification
            $notif_data = array(
                'client' => $customer,
                'subject' => 'Field Purchase Payment Update',
                'message' => 'Dear ' . $client . ', your payment for ' . $field_name . ' (' . $size . ' plot (' . $sqm . ' m²)) has been received. Total paid so far: ₦' . $fmt_total_paid . ', remaining balance: ₦' . $fmt_remaining . '. Once fully paid, your annual rent of ₦' . $fmt_rent . ' will begin.',
                'post_date' => $date,
            );

            $add_data = new add_data('notifications', $notif_data);
            $add_data->create_data();
        }
        $amount = number_format($amount, 2);
        $trx_date = date("jS F Y, h:ia", strtotime($date));
        $message = $email_message;
        //insert into notifications
        $notif_data = array(
            'client' => $customer,
            'subject' => 'Field purchase Payment Confirmation',
            'message' => 'Dear '.$client.',
            We confirm the receipt of your payment of ₦'.$amount.' on '.$trx_date.' towards your Field purchase.
            Transaction ID: '.$receipt.'
            Your account has been updated accordingly. Thank you for your commitment.
            
            If you have any questions or need a receipt, feel free to contact us

            Warm regards,
            '.$company.'
            Customer Support',
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
            $mail->Host = 'smtppro.zoho.com';
            $mail->Port = 465; 
            $mail->Username = 'info@davidorlahfarms.com';
            $mail->Password = 'Info_DFarms@2520';   

            $mail->IsHTML(true);
            $mail->From="info@davidorlahfarms.com";
            $mail->FromName=$from_name;
            $mail->Sender=$from;
            $mail->AddReplyTo($from, $from_name);
            $mail->Subject = $subject;
            $mail->Body = $body;
            $mail->AddAddress($to);

            if(!$mail->Send()){
                return "Failed to send mail";
            } else {
                return "Message Sent Successfully";
            }
        }
        
        $to = $customer_email;
        $from = 'info@davidorlahfarms.com';
        $from_name = "$company";
        $name = "$company";
        $subj = 'Field Purchase Update';
        $msg = "<div>$message</div>";
        
        $error=smtpmailer($to, $from, $name ,$subj, $msg);
        
?>
    <div id="printBtn">
        <button onclick="printPaymentReceipt('<?php echo $receipt?>')">Print Receipt <i class="fas fa-print"></i></button>
    </div>
<?php

        echo "<p style='color:green; margin:5px 50px'>Payment posted successfully!</p>";
    // }
}