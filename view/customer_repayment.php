<div id="package">
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['customer'])){
        $customer_id = htmlspecialchars(stripslashes($_GET['customer']));
    //get customer details
    $get_details = new selects();
    $cus = $get_details->fetch_details_group('customers', 'customer', 'customer_id', $customer_id);
    $client = $cus->customer;
    //check for current loan
    $rows = $get_details->fetch_details_2cond('assigned_fields', 'customer', 'contract_status', $customer_id, 2);
    if(is_array($rows)){
        foreach($rows as $row){
            $loan = $row->assigned_id;
            //check for rent schedule start date
            $schedules = $get_details->fetch_details_condLimitAsc('rent_schedule', 'assigned_id', $loan, 1, 'due_date');
            if(is_array($schedules)){
                foreach($schedules as $schedule){
                    $start_date = $schedule->due_date;
                }
            }else{
                $start_date = "N/A";
            }
            //get land size
            $sze = $get_details->fetch_details_group('assigned_fields', 'field_size', 'assigned_id', $loan);
            $size = $sze->field_size;
            //check for rent schedule last due date
            $due_dates = $get_details->fetch_details_condLimitDesc('rent_schedule', 'assigned_id', $loan, 1, 'due_date');
            if(is_array($due_dates)){
                foreach($due_dates as $due_date){
                    $end_date = $due_date->due_date;
                }
            }else{
                $end_date = "N/A";
            }   
    

?>
<div class="info" style="margin:0!important; width:90%!important"></div>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed" href="javascript:void(0)" onclick="showPage('rent_payments.php')"><i class="fas fa-angle-double-left"></i> Return</a>
    <div class="displays allResults" style="width:100%;">
    <section id="prescriptions">
            <div class="add_user_form" style="margin:0!important;width:100%!important">
                <h3 style="background:var(--otherColor);color:#fff;text-align:center;font-size:.9rem;padding:5px">Rent Details for <?php echo $client?></h3>
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
                            <label for="amount" style="text-align:left!important;">Size</label>
                            <input type="text" value="<?php echo number_format($size, 2)?>plot (<?php echo number_format($row->field_size * 500)?>m&sup2)" readonly>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Rent (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->annual_rent, 2)?>" readonly style="color:green">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Lease Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y, h:ia", strtotime($row->assigned_date))?>" readonly>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">First Due Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y", strtotime($start_date))?>" readonly>
                        </div>
                        
                        <div class="data" style="width:24%;">
                            <label for="duration" style="text-align:left!important;">Duration</label>
                            <input type="text" value="<?php echo $row->contract_duration?> Years" readonly>
                        </div>
                        <!-- <div class="data" style="width:24%;">
                            <label for="repayment" style="text-align:left!important;">Repayment Frequency</label>
                            <input type="text" value="<?php echo $row->frequency?>" readonly>
                        </div> -->
                        
                        
                        <!-- <div class="data" style="width:24%;">
                            <label for="">Total Payable Amount:</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->total_repayment, 2)?>" readonly style="color:var(--tertiaryColor)">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for=""><?php echo $row->frequency?> Installment:</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->installment, 2)?>" readonly style="color:var(--otherColor)">
                        </div> -->
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Due Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y", strtotime($end_date))?>" readonly>
                        </div>
                        
                    </div>
                </section>   
            </div>
        </section>
        <section style="width:100%">
             <h3 style="background:var(--labColor); text-align:center; color:#fff; font-size:.9rem;padding:5px;">Rent payment Schedule</h3>
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
                            $repays = $get_details->fetch_details_cond('rent_schedule', 'assigned_id', $row->assigned_id);
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

                                    $button = "<a style='border-radius:15px; background:var(--tertiaryColor);color:#fff; padding:3px 6px; box-shadow:1px 1px 1px #222; border:1px solid #fff' href='javascript:void(0)' onclick=\"showPage('loan_payment.php?schedule={$repay->repayment_id}&customer={$customer_id}')\" title='Post payment'>Add Payment <i class='fas fa-hand-holding-dollar'></i></a>";

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
                        <?php $n++; }; ?>
                    </tbody>
                </table>
                <?php
                    //get total due
                    $tls = $get_details->fetch_sum_single('rent_schedule', 'amount_due', 'assigned_id', $row->assigned_id);
                    foreach($tls as $tl){
                        $total_due = $tl->total;
                    }
                    //get total paid
                    $paids = $get_details->fetch_sum_single('rent_schedule', 'amount_paid', 'assigned_id', $row->assigned_id);
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
        </section>
    </div>
    
</div>
<?php
        }
            }else{
                ?>
                <div class="not_available"><p><strong><i class="fas fa-exclamation-triangle" style="color: #cfb20e;"></i> No Active Field</strong><br>The selected customer have no active Field. Cannot proceed!</p>
                <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('rent_payment.php')"><i class="fas fa-angle-double-left"></i> Return</a></div>
        <?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>