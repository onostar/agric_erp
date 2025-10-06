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
        $trans_type = "labour cost";
            $user = $_SESSION['user_id'];
            $total_amount = htmlspecialchars(stripslashes($_POST['total_amount']));
            $task = htmlspecialchars(stripslashes($_POST['task']));
            $store = htmlspecialchars(stripslashes($_POST['store']));
            $contra = htmlspecialchars(stripslashes($_POST['contra']));
            $exp_head = htmlspecialchars(stripslashes($_POST['exp_head']));
            $details = "Labour Cost";
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
            //get task details
            $get_details = new selects();
            $invoices = $get_details->fetch_details_cond('tasks', 'task_id', $task);
            foreach($invoices as $receipt){
                $trans_date = $receipt->post_date;
                // $trans_date = $date;
            }
            
            //get ledger type
            $types = $get_details->fetch_details_cond('ledgers', 'ledger_id', $exp_head);
            foreach($types as $type){
                $labour_ledger = $type->acn;
                $labour_type = $type->account_group;
                $labour_sub = $type->sub_group;
                $labour_class = $type->class;
            };
            
            //insert into labour payment
            $data = array(
                'task' => $task,
                'amount' => $total_amount,
                'contra' => $contra,
                'exp_head' => $exp_head,
                'posted_by' => $user,
                'store' => $store,
                'trans_date' => $trans_date,
                'post_date' => $date,
                'trx_number' => $trx_num
            );
            $add_payment = new add_data('labour_payments', $data);
            $add_payment->create_data();
               
            if($add_payment){
                //update labour payment status on task table
                $update_status = new Update_table();
                $update_status->update('tasks', 'payment_status', 'task_id', 1, $task);
                //insert into transaction table
                $amount = $grand_total;
                //get contra ledger details
                $invs = $get_details->fetch_details_cond('ledgers', 'ledger_id', $contra);
                foreach($invs as $inv){
                    $contra_ledger = $inv->acn;
                    $contra_type = $inv->account_group;
                    $contra_sub_group = $inv->sub_group;
                    $contra_class = $inv->class;

                }
                $debit_data = array(
                    'account' => $labour_ledger,
                    'account_type' => $labour_type,
                    'sub_group' => $labour_sub,
                    'class' => $labour_class,
                    'debit' => $amount,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'details' => $details,
                    'trans_date' => $trans_date,
                    'store' => $store
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
                    'details' => $details,
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
           
                echo "<div class='success'><p>Transaction posted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }
            
                
?>
<?php
 
    }else{
        header("Location: ../index.php");
    } 
?>