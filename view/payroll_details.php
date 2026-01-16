<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_SESSION['user_id'])){
        if(isset($_GET['payroll'])){
            $payroll = $_GET['payroll'];
            $get_details = new selects();

            //get payroll details
            $results = $get_details->fetch_details_cond('payroll','payroll_id',$payroll);
            foreach($results as $result){
                $staff = $result->staff;
                $total_working_days = $result->working_days;
                $days_at_work = $result->days_at_work;
                $leave_days = $result->leave_days;
                $suspension_days = $result->suspension_days;
                $total_absent_days = $result->absent_days;
                $late_days = $result->late_days;
                $basic = $result->basic_salary;
                $housing = $result->housing;
                $medical = $result->medical;
                $transport = $result->transport;
                $utility = $result->utility;
                $others = $result->other_allowance;
                $gross = $result->gross_pay;
                $tax = $result->tax;
                $taxable_income = $result->taxable_income;
                $rate = $result->tax_rate;
                $pension = $result->pension;
                $absent_fee = $result->absence_penalty;
                $lateness_fee = $result->lateness_penalty;
                $loan = $result->loan_repayment;
                $other_deductions = $result->other_deductions;
                $daily_pay = $result->daily_pay;
                $net_after_tax = $result->net_after_tax;
                $net_pay = $result->net_pay;
                $date_generated = $result->date_generated;
                $prepared_by = $result->prepared_by;
                $status = $result->payroll_status;
                $payroll_date = $result->payroll_date;
            }
            
            //get staff details
            $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
            foreach($rows as $row){
                $full_name = $row->last_name." ".$row->other_names;
            }
            $month = date("Y-m-d");
?>

<div id="payrolls" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('approve_payroll.php')" title="Return to payroll">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:40%; margin:20px"></div>
    <div class="add_user_form" style="width:90%; margin:0px">
        <h3 style="background:var(--otherColor)"><?php echo date("F, Y", strtotime($payroll_date))?> Pay slip Details for <?php echo $full_name?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left; background:#cdcdcd; padding:5px;">
                <div class="data" style="width:23%">
                    <label for="basic_salary">Total Working Days</label>
                    <input type="text" value="<?php echo $total_working_days?> days" readonly>
                    <input type="hidden" id="working_days" name="working_days" value="<?php echo $total_working_days?>" readonly>
                </div>
                <div class="data" style="width:23%">
                    <label for="basic_salary">Days at work</label>
                    <input type="text" value="<?php echo $days_at_work?> days" readonly>
                    <input type="hidden" id="days_at_work" name="days_at_work" value="<?php echo $days_at_work?>" readonly>
                </div>
                <div class="data" style="width:23%">
                    <label for="basic_salary">Days on Leave</label>
                    <input type="text" value="<?php echo $leave_days?> days" readonly>
                    <input type="hidden" id="leave_days" name="leave_days" value="<?php echo $leave_days?>" readonly>
                </div>
                <div class="data" style="width:23%">
                    <label for="basic_salary">Days on Suspension</label>
                    <input type="text" value="<?php echo $suspension_days?> days" readonly>
                    <input type="hidden" id="suspension_days" name="suspension_days" value="<?php echo $suspension_days?>" readonly>
                </div>
                <div class="data" style="width:23%">
                    <label for="basic_salary">Total Absent Days</label>
                    <input type="text" value="<?php echo $total_absent_days?> days" readonly>
                    <input type="hidden" id="absent_days" name="absent_days" value="<?php echo $absent_days?>" readonly>
                </div>
                <div class="data" style="width:23%">
                    <label for="basic_salary">Total Late Days</label>
                    <input type="text" value="<?php echo $late_days?> days" readonly>
                </div>
            </div>
            <hr>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="staff" id="staff" value="<?php echo $staff?>">
                <div class="data" style="width:23%">
                    <label for="basic_salary">Basic Salary (NGN)</label>
                    <input type="text" id="basic_salary" name="basic_salary" value="<?php echo number_format($basic, 2)?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                   <label for="housing"> Housing Allow. (NGN)</label>
                   <input type="text" name="housing" id="housing" required value="<?php echo number_format($housing, 2)?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="medical">Medical Allow. (NGN)</label>
                    <input type="text" name="medical" id="medical" required value="<?php echo number_format($medical, 2)?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="transport">Transport Allow. (NGN)</label>
                    <input type="text" name="transport" id="transport" required value="<?php echo number_format($transport, 2)?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="utility">Utility Allowance (NGN)</label>
                    <input type="text" name="utility" id="utility" required value="<?php echo number_format($utility, 2)?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="others">Other Allowance (NGN)</label>
                    <input type="text" name="other_allow" id="other_allow" required value="<?php echo number_format($others, 2)?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Gross Pay (NGN)</label>
                    <input type="text" required value="<?php echo number_format($gross, 2)?>" style="background:#cdcdcd;" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Annual Taxable Income (NGN)</label>
                    <input type="text" required value="<?php echo number_format($taxable_income, 2)?>" style="background:#cdcdcd" readonly>
                </div>
            </div>
            <h4 style="margin:5px 0 0 0; font-size:.9rem;">Deductions</h4>
            <hr>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <div class="data" style="width:23%;">
                    <label for="tax">Tax (NGN) - <span style='color:red'><?php echo number_format($rate, 1)?>% Applied</span></label>
                    <input type="text" name="tax" id="tax" required value=<?php echo number_format($tax, 2)?> readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="pension">Pension (NGN) - <span style='color:red'>8% Applied</span></label>
                    <input type="text" name="pension" id="pension" required value=<?php echo number_format($pension, 2)?> readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="pay_after_deductions">Net Pay after Deductions (NGN)</label>
                    <input type="text"  value=<?php echo number_format($net_after_tax,2)?> style="background:#cdcdcd" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="pay_after_deductions">Net Daily Pay (NGN)</label>
                    <input type="text" value=<?php echo number_format($daily_pay, 2)?>  readonly>
                   
                </div>
                <div class="data" style="width:23%;">
                    <label for="absence">Absence Deductions (NGN)</label>
                    <input type="text" name="absence" id="absence" required value=<?php echo number_format($absent_fee, 2)?> readonly>
                </div>
                
                <div class="data" style="width:23%;">
                    <label for="loans">Loan Repayment (NGN)</label>
                    <input type="text" name="loans" id="loans" required value=<?php echo number_format($loan, 2)?> readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="others">Other Deductions (NGN)</label>
                    <input type="text" name="others" id="others" required value=<?php echo $other_deductions?> readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Net Pay (NGN)</label>
                    <input type="text" name="net_salary" id="net_salary" value="<?php echo number_format($net_pay, 2)?>" required style="background:green; color:#fff" readonly>
                </div>
            </div>
            <div class="inputs">
                <div class="data"style="width:auto">
                    <a style="padding:5px 8px; border-radius:15px;background:var(--tertiaryColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="approvePayroll('<?php echo $payroll?>')" title="Approve payroll">Approve <i class="fas fa-check-square"></i></a>
                    <a style="padding:5px 8px; border-radius:15px;background:brown;color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="declinePayroll('<?php echo $payroll?>')" title="Decline payroll">Decline <i class="fas fa-close"></i></a>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php
        }
    }else{
        echo "Your session has expired! Login to continue";
    }