<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = htmlspecialchars(stripslashes($_POST['posted']));
    $receipt = htmlspecialchars(stripslashes($_POST['invoice']));
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $store = htmlspecialchars(stripslashes($_POST['store']));
    $mode = htmlspecialchars(stripslashes($_POST['payment_mode']));
    $balance = htmlspecialchars(stripslashes($_POST['balance']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $assigned = htmlspecialchars(stripslashes($_POST['assigned_id']));
    $bank = htmlspecialchars(stripslashes($_POST['bank']));
    $trans_date = htmlspecialchars(stripslashes($_POST['trans_date']));
    $details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
    $trans_type = "Field Documentation Payment";
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
        'details' => 'Documentation payment',
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
    $get_details = new selects();
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
       
        //get assigned details
        $loan_details = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $assigned);
        foreach($loan_details as $loan){
            $purchase_cost = $loan->total_due;
            $duration = $loan->contract_duration;
            $field_id = $loan->field;
            $size = $loan->field_size;
            $documentation = $loan->documentation;
            // $total_payable = $loan->total_repayment;
        }
        //getfield details
        $fields = $get_details->fetch_details_cond('fields', 'field_id', $field_id);
        foreach($fields as $fd){
            $field_name = $fd->field_name;
            // $size = $fd->field_size;
            $location = $fd->location;
        }
        $doc_data = array(
            'assigned_id' => $assigned,
            'client' => $customer,
            'field' => $field_id,
            'amount' => $amount,
            'trx_date' => $trans_date,
            'trx_number' => $trx_num,
            'invoice' => $receipt,
            'payment_mode' => $mode,
            'bank' => $bank,
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store
        );
        $add_doc = new add_data('documentation_fees', $doc_data);
        $add_doc->create_data();
        $new_balance = $balance - $amount;

        if($new_balance <= 0){
            //documentation status
            $update = new Update_table();
            $update->update('assigned_fields', 'documentation_status', 'assigned_id', 1, $assigned);
        }
        
        //accounting entries
        
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
            $bnk = $get_details->fetch_details_group('banks', 'bank', 'bank_id', $bank);
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
        $debit_data = array(
            'account' => $dr_ledger,
            'account_type' => $dr_type,
            'sub_group' => $dr_group,
            'class' => $dr_class,
            'details' => 'Field Documentation payment',
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
            'details' => 'Field Documentation Payment',
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
            'details' => 'Field Documentation Payment',
            'trx_number' => $trx_num,
            'amount' => $amount,
            'trans_type' => 'inflow',
            'activity' => 'operating',
            'post_date' => $date,
            'posted_by' => $user,
            'store' => $store
        );
        $add_flow = new add_data('cash_flows', $flow_data);
        $add_flow->create_data();
        
        
        $check_repayments = $get_details->fetch_sum_single('documentation_fees', 'amount', 'assigned_id', $assigned);
        foreach($check_repayments as $rep){
            $total_paid = $rep->total;
        }
        $fmt_total_cost = number_format($documentation, 2);
        $fmt_total_paid = number_format($total_paid, 2);
        // $fmt_rent = number_format($annual_rent, 2);
        $fmt_remaining = number_format($documentation - $total_paid, 2);
        $sqm = $size * 500;
        if($total_paid === $documentation){
           

            // build email message for full payment
            
            $email_message = "
            <p>Dear $client,</p>
            <p>Congratulations! You have successfully completed your field documentation payment.</p>
            <h3>Purchase Summary:</h3>
            <ul>
                <li><strong>Field:</strong> $field_name</li>
                <li><strong>Location:</strong> $location</li>
                <li><strong>Size:</strong> $size Plot ($sqm m²)</li>
                <li><strong>Contract Duration:</strong> $duration year(s)</li>
                <li><strong>Total DOcumentation Cost:</strong> ₦$fmt_total_cost</li>
                <li><strong>Total Paid:</strong> ₦$fmt_total_paid</li>
                <li><strong>Amount Remaining:</strong> ₦0.00 (Fully Paid)</li>
                
            </ul>
            <p>Please note that your <strong>Farm Management Contract</strong> and <strong>Deed of Assignment</strong> will be available in two weeks from today. While your <strong>Survey Documents</strong> will be available after allocation.</p>
            <p>Thank you for your trust in <strong>Davidorlah Nigeria Limited</strong>. We look forward to building a fruitful partnership with you.</p>
            <br><p>Warm regards,<br><strong>Farm Management Team</strong><br>Davidorlah Nigeria Limited</p>";

            // insert notification
            $notif_data = array(
                'client' => $customer,
                'subject' => 'Field Documentation payment Completed',
                'message' => 'Dear ' . $client . ', Documentation for your field (' . $field_name . ' - ' . $size . ' plot ('.$sqm.'m²)) has been fully paid. ',
                'post_date' => $date,
            );

            $add_data = new add_data('notifications', $notif_data);
            $add_data->create_data();
        } else {
            // partial payment message
            $email_message = "
            <p>Dear $client,</p>
            <p>Your recent payment for your field documentation has been received successfully for your field purchase.</p>
            <h3>Purchase Summary:</h3>
            <ul>
                <li><strong>Field:</strong> $field_name</li>
                <li><strong>Location:</strong> $location</li>
                <li><strong>Size:</strong> $size Plot ($sqm m²)</li>
                <li><strong>Contract Duration:</strong> $duration year(s)</li>
                <li><strong>Total Documentation Cost:</strong> ₦$fmt_total_cost</li>
                <li><strong>Total Paid So Far:</strong> ₦$fmt_total_paid</li>
                <li><strong>Amount Remaining:</strong> ₦$fmt_remaining</li>
               
            </ul>
            
            <p>Thank you for your continued commitment.</p>
            <br><p>Warm regards,<br><strong>Farm Management Team</strong><br>Davidorlah Nigeria Limited</p>";

            // partial payment notification
            $notif_data = array(
                'client' => $customer,
                'subject' => 'Field Documentation Payment Update',
                'message' => 'Dear ' . $client . ', your documentation payment for ' . $field_name . ' (' . $size . ' plot ('.$sqm.'m²)) has been received. Total paid so far: ₦' . $fmt_total_paid . ', remaining balance: ₦' . $fmt_remaining ,
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
            'subject' => 'Field Documentation Payment Confirmation',
            'message' => 'Dear '.$client.',
            We confirm the receipt of your payment of ₦'.$amount.' on '.$trx_date.' towards your Field documentaion.
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
        $subj = 'Field Documentation Payment Confirmation - Davidorlah Nigeria Ltd';
        $msg = "<div>$message</div>";
        
        $error=smtpmailer($to, $from, $name ,$subj, $msg);
        
?>
    <div id="printBtn">
        <button onclick="printDocReceipt('<?php echo $receipt?>')">Print Receipt <i class="fas fa-print"></i></button>
    </div>
<?php

        echo "<p style='color:green; margin:5px 50px'>Payment posted successfully!</p>";
    // }
}