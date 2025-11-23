<div id="package">
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['assigned_id']) && isset($_GET['payment'])){
        $payment_id = htmlspecialchars(stripslashes($_GET['payment']));
        $assigned_id = htmlspecialchars(stripslashes($_GET['assigned_id']));
        //get customers from payment evidence
        $get_details = new selects();
        $pays = $get_details->fetch_details_cond('payment_evidence', 'payment_id', $payment_id);
        foreach($pays as $pay){
            $customer_id = $pay->customer;
            $amount = $pay->amount;
        }

    //get customer details
    $cus = $get_details->fetch_details_cond('customers', 'customer_id', $customer_id);
    foreach($cus as $cu){
        $client = $cu->customer;
        $acn = $cu->acn;
    }
    //check for current loan
    $rows = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $assigned_id);
    if(is_array($rows)){
        foreach($rows as $row){
            $loan = $row->assigned_id;
        
    

?>
<div class="info" style="margin:0!important; width:90%!important"></div>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed" href="javascript:void(0)" onclick="showPage('approve_customer_payment.php')"><i class="fas fa-angle-double-left"></i> Return</a>
    <div class="displays allResults" style="width:100%;">
    <section id="prescriptions">
            <div class="add_user_form" style="margin:0!important;width:100%!important">
                <h3 style="background:var(--otherColor);color:#fff;text-align:center;font-size:.9rem;padding:5px">Field Purchase Details for <?php echo $client?></h3>
                <section style="text-align:left;">
                    <div class="inputs" style="align-items:flex-end; justify-content:left; gap:.5rem; padding:0!important">
                       <div class="data" style="width:24%;">
                            <label for="loan_name">Field:</label>
                            <?php
                                $prods = $get_details->fetch_details_cond('fields', 'field_id', $row->field);
                                if(is_array($prods)){
                                    foreach($prods as $prod){
                                        $product_name = $prod->field_name;
                                    }
                                }
                            ?>
                            <input type="text" value="<?php echo $product_name?>" readonly>
                       </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Purchase Cost (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->purchase_cost, 2)?>" readonly style="color:green">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Discount (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->discount, 2)?>" readonly style="color:green">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Total Payable (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->total_due, 2)?>" readonly style="color:green">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Purchase Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y, h:ia", strtotime($row->assigned_date))?>" readonly>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Contract Start Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y, h:ia", strtotime($row->start_date))?>" readonly>
                        </div>
                        
                        <div class="data" style="width:24%;">
                            <label for="duration" style="text-align:left!important;">Payment Duration</label>
                            <?php
                                if($row->payment_duration == 1){
                                    $pay_duration = "Outright Purhase";
                                }else{
                                    $pay_duration = $row->payment_duration." months";
                                }
                            ?>
                            <input type="text" value="<?php echo $pay_duration?> " readonly>
                        </div>
                        <!-- <div class="data" style="width:24%;">
                            <label for=""> Installment:</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->installment, 2)?>" readonly style="color:var(--otherColor)">
                        </div> -->
                        <div class="data" style="width:24%;">
                            <label for="repayment" style="text-align:left!important;">Rent Contract Duration</label>
                            <input type="text" value="<?php echo $row->contract_duration?> Years" readonly>
                        </div>
                        
                        
                        <div class="data" style="width:24%;">
                            <label for="">Annual Rent:</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->annual_rent, 2)?>" readonly style="color:var(--tertiaryColor)">
                        </div>
                        
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Due Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y", strtotime($row->due_date))?>" readonly>
                        </div>
                        
                    </div>
                </section>   
            </div>
        </section>
        <!-- payment form -->
        <?php
        //get latest schedule
        $schedules = $get_details->fetch_details_2condLimitAsc('field_payment_schedule', 'assigned_id', $assigned_id, 'payment_status', 0, 1, 'repayment_id');
        if(is_array($schedules)){
            foreach($schedules as $schedule){
                $schedule_id = $schedule->repayment_id;
            }
        }
        //generate deposit receipt
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 3);
            $ran_num .= $random_num;
        }
        $receipt_id = "LP".$todays_date.$ran_num.$user.$schedule_id;
        //get balance from transactions
        $bals = $get_details->fetch_account_balance($acn);
        if(gettype($bals) == 'array'){
            foreach($bals as $bal){
                $balance = $bal->balance;
            }
        }
        //get loan details
        $lns = $get_details->fetch_details_cond('field_payment_schedule', 'repayment_id', $schedule_id);
        foreach($lns as $lns){
            $amount_due = $lns->amount_due;
            $payment_status = $lns->payment_status;
        }
       //get total paid
       $ttls = $get_details->fetch_sum_single('field_payment_schedule', 'amount_paid', 'assigned_id', $assigned_id);
       if(gettype($ttls) == 'array'){
            foreach($ttls as $ttl){
                $total_paid = $ttl->total;
            }
        }else{
            $total_paid = 0;
        }
       //get total due
       $ttlx = $get_details->fetch_sum_single('field_payment_schedule', 'amount_due', 'assigned_id', $assigned_id);
       if(gettype($ttlx) == 'array'){
            foreach($ttlx as $ttx){
                $total_due = $ttx->total;
            }
        }else{
            $total_due = 0;
        }
        //
        $debt = $total_due - $total_paid;

?>


    <div class="fund_account" style="width:100%; margin:5px 0;">
        <h3 style="background:var(--labColor); text-align:left">Post customer field payments</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <div class="details_forms">
            <section class="addUserForm">
                <div class="inputs" style="flex-wrap:wrap">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $receipt_id?>">
                    <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                    <input type="hidden" name="customer" id="customer" value="<?php echo $customer_id?>">
                    <input type="hidden" name="payment_id" id="payment_id" value="<?php echo $payment_id?>">
                    <input type="hidden" name="balance" id="balance" value="<?php echo $debt?>">
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                    <input type="hidden" name="schedule" id="schedule" value="<?php echo $schedule_id?>">
                    
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="amount"> Transaction Date</label>
                        <input type="date" name="trans_date" id="trans_date" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <label for="amount"> Amount paid</label>
                        <input type="text"  readonly value="<?php echo "₦" . number_format($amount, 2)?>">
                        <input type="hidden" name="amount" id="amount" value="<?php echo $amount?>">
                    </div>
                    <div class="data" style="width:45%">
                        <label for="Payment_mode"><span class="ledger">Dr. Ledger</span> (Cash/Bank)</label>
                        <select name="payment_mode" id="payment_mode" onchange="checkMode(this.value)">
                            <option value=""selected>Select payment option</option>
                            <option value="Cash">Cash</option>
                            <option value="POS">POS</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="data" id="selectBank"  style="width:100%!important">
                        <select name="bank" id="bank">
                            <option value=""selected>Select Bank</option>
                            <?php
                                $get_bank = new selects();
                                $rows = $get_bank->fetch_details('banks', 10, 10);
                                foreach($rows as $row):
                            ?>
                            <option value="<?php echo $row->bank_id?>"><?php echo $row->bank?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="details"> Description</label>
                        <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction"></textarea>
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <button type="submit" id="post_exp" name="post_exp" onclick="payField('package')">Post payment <i class="fas fa-cash-register"></i></button>
                    </div>
                </div>
            </section>
            <section class="customer_details" style="height:100%;">
                <div class="inputs">
                    <div class="data">
                        <label for="customer_id">Customer ID:</label>
                        <input type="text" value="<?php echo $acn?>">
                    </div>
                    <div class="data">
                        <label for="customer_name"><span class="ledger" style="color:#fff">Cr. Ledger</span> (Client):</label>
                        <input type="text" value="<?php echo $client?>">
                    </div>
                    <?php if($balance >= 0){?>
                    <div class="data">
                        <label for="balance">Account balance:</label>
                        <input type="text" value="<?php echo "₦".number_format($balance, 2)?>" style="color:red;">
                    </div>
                    <?php }else{?>
                    <div class="data">
                        <label for="balance">Account balance:</label>
                        <input type="text" value="<?php echo "₦".number_format(0, 2)?>" style="color:green;">
                    </div>
                    <?php }?>
                    <div class="data">
                        <label for="balance">Payment Due:</label>
                        <input type="text" value="<?php echo "₦".number_format($debt, 2)?>" style="color:red;">
                    </div>
                </div>
            </section> 
        </div>
    </div>
       <!--  <section style="width:100%">
             <h3 style="background:var(--labColor); text-align:center; color:#fff; font-size:.9rem;padding:5px;">Payment Schedule</h3>
            <div class="displays allResults" style="width:100%!important; margin:0!important">
                <table id="item_list_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--tertiaryColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Amount Due</td>
                            <td>Amount Paid</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody id="result">
                        <?php
                            $n = 1;
                            $repays = $get_details->fetch_details_cond('field_payment_schedule', 'assigned_id', $row->assigned_id);
                            if(is_array($repays)){
                            $allow_next = true; // True until first unpaid schedule is found
                            foreach($repays as $index => $repay){
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td><?php echo date("d-M-Y", strtotime($repay->due_date))?></td>
                            <td style="color:var(--secondaryColor)"><?php echo "₦".number_format($repay->amount_due, 2)?></td>
                            <td><?php echo "₦".number_format($repay->amount_paid, 2)?></td>
                            <td>
                                <?php
                                    $date_due = new DateTime($repay->due_date);
                                    $today = new DateTime();

                                    $button = "<a style='border-radius:15px; background:var(--tertiaryColor);color:#fff; padding:3px 6px; box-shadow:1px 1px 1px #222; border:1px solid #fff' href='javascript:void(0)' onclick=\"showPage('field_payment.php?schedule={$repay->repayment_id}&customer={$customer_id}')\" title='Post payment'>Add Payment <i class='fas fa-hand-holding-dollar'></i></a>";

                                    if($repay->payment_status == "1"){
                                        echo "<span style='color:var(--tertiaryColor);'>Paid <i class='fas fa-check-circle'></i></span>";
                                    } else {
                                        // First unpaid schedule (or any overdue) is allowed to pay only if previous schedules are paid
                                        if($allow_next || $date_due < $today){
                                            if($date_due > $today){
                                                echo "<span style='color:var(--primaryColor);'><i class='fas fa-spinner'></i> Pending </span> {$button}";
                                            } else {
                                                echo "<span style='color:red;'><i class='fas fa-clock'></i> Overdue </span> {$button}";
                                            }
                                            $allow_next = false; // After showing Add Payment for one, others must wait
                                        } else {
                                            echo "<span style='color:#999;'>Waiting for previous payment <i class='fas fa-lock'></i></span>";
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php $n++; }; }?>
                    </tbody>
                </table>
                <?php
                    //get total due
                    $tls = $get_details->fetch_sum_single('field_payment_schedule', 'amount_due', 'assigned_id', $row->assigned_id);
                    foreach($tls as $tl){
                        $total_due = $tl->total;
                    }
                    //get total paid
                    $paids = $get_details->fetch_sum_single('field_payment_schedule', 'amount_paid', 'assigned_id', $row->assigned_id);
                    foreach($paids as $paid){
                        $total_paid = $paid->total;
                    }
                    $balance = $total_due - $total_paid;
                ?>
                    <div class="totals" style="display:flex; gap:1rem; justify-content:right">
                        <?php
                        echo "<p class='total_amount' style='background:green; color:#fff; text-decoration:none; width:auto; float:right; padding:10px;font-size:1rem;'>Total Paid: ₦".number_format($total_paid, 2)."</p>";
                        echo "<p class='total_amount' style='background:red; color:#fff; text-decoration:none; width:auto; float:right; padding:10px;font-size:1rem;'>Total Due: ₦".number_format($balance, 2)."</p>";
                    ?>
                    </div>
                
            </div>
        </section> -->
    </div>
    
</div>
<?php
        }
            }else{
                ?>
                <div class="not_available"><p><strong><i class="fas fa-exclamation-triangle" style="color: #cfb20e;"></i> No Active Field</strong><br>The selected customer have no active Field. Cannot proceed!</p>
                <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('approve_customer_payment.php')"><i class="fas fa-angle-double-left"></i> Return</a></div>
        <?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>