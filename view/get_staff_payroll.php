<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_SESSION['user_id'])){
        if(isset($_GET['staff']) && isset($_GET['salary_id'])){
            $staff = htmlspecialchars(stripslashes($_GET['staff']));
            $salary_id = htmlspecialchars(stripslashes($_GET['salary_id']));
            //get staff details
            $get_details = new selects();
            $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
            foreach($rows as $row){
                $full_name = $row->last_name." ".$row->other_names;
            }
            $payroll_date = date("Y-m-d");
            //get salary details
            $details = $get_details->fetch_details_cond('salary_structure', 'salary_id', $salary_id);
            foreach($details as $detail){
                $basic = $detail->basic_salary;
                $utility = $detail->utility_allowance;
                $medical = $detail->medical_allowance;
                $transport = $detail->transport_allowance;
                $housing = $detail->housing_allowance;
                $others = $detail->other_allowance;
                $total = $detail->total_earnings;
            }
            //get taxale income
            $taxable_income = $basic + $utility + $transport + $housing + $others;
            //get tax
            $rates = $get_details->fetch_tax_rate($taxable_income);
            if(is_array($rates)){
                foreach($rates as $tx){
                    $rate = $tx->tax_rate;
                    $title = $tx->title;
                }
            }else{
                $rate = 0;
                $title = "No tax";
            }
            $tax = round(($rate/100) * $taxable_income, 2);
            //get employee pension
            $pension_income = $basic + $transport + $housing;
            $pension = round((8/100) * $pension_income, 2);
            //get employercontribution
            $employer_contribution = round((10/100) * $pension_income, 2);

            //fetch absent fee
            $abs = $get_details->fetch_details_group('penalty_fees', 'amount', 'penalty', 'ABSENT');
            $absent_penalty = $abs->amount;
            //fetch lateness fee
            $lts = $get_details->fetch_details_group('penalty_fees', 'amount', 'penalty', 'LATENESS');
            $lateness_penaltly = $abs->amount;

            //fetch late days
            $late_days = $get_details->fetch_late_days($staff);
            $lateness_fee = $late_days * $lateness_penaltly;

            //fetch days present at work
            $days_at_work = $get_details->fetch_staff_work_days($staff);
            /* echo "Days at work: $days_at_work";
            echo "late days: $late_days"; */

            //fetch leave days
            $leave_days = $get_details->fetch_leave_days($staff);
           
            //fetch suspension days
            $suspension_days = $get_details->fetch_suspension_days($staff);
            

            //total working days
            $total_working_days = $get_details->fetch_total_working_days();

            //claulate absent days
            $absent_days = $total_working_days - ($days_at_work + $leave_days);
            

            //get absent penalty fee
            $absent_fee = $absent_penalty * $absent_days;

            //calculate defaultnet pay
            $net_pay = $total - ($tax + $pension + $lateness_fee + $absent_fee)
?>

<div id="salary_structure" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('generate_payroll.php')" title="Return to salary structure">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:40%; margin:20px"></div>
    <div class="add_user_form" style="width:90%; margin:0px">
        <h3 style="background:var(--tertiaryColor)">Generate <?php echo date("F, Y")?> Payslip for <?php echo $full_name?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left; background:#cdcdcd; padding:5px;">
                <input type="hidden" id="payroll_date"id="payroll_date" value="<?php echo $payroll_date?>">
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
                    <input type="text" value="<?php echo $absent_days?> days" readonly>
                    <input type="hidden" id="absent_days" name="absent_days" value="<?php echo $absent_days?>" readonly>
                </div>
            </div>
            <hr>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="staff" id="staff" value="<?php echo $staff?>">
                <div class="data" style="width:23%">
                    <label for="basic_salary">Basic Salary (NGN)</label>
                    <input type="text" value="<?php echo number_format($basic, 2)?>" readonly>
                    <input type="hidden" id="basic_salary" name="basic_salary" value="<?php echo $basic?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                   <label for="housing"> Housing Allow. (NGN)</label>
                   <input type="text" value="<?php echo number_format($housing, 2)?>" readonly>
                   <input type="hidden" name="housing" id="housing" value="<?php echo $housing?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="medical">Medical Allow. (NGN)</label>
                    <input type="text" value="<?php echo number_format($medical, 2)?>" readonly>
                    <input type="hidden" name="medical" id="medical" value="<?php echo $medical?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="transport">Transport Allow. (NGN)</label>
                    <input type="text" value="<?php echo number_format($transport, 2)?>" readonly>
                    <input type="hidden" name="transport" id="transport" value="<?php echo $transport?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="utility">Utility Allowance (NGN)</label>
                    <input type="text" value="<?php echo number_format($utility, 2)?>" readonly>
                    <input type="hidden" name="utility" id="utility" value="<?php echo $utility?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="others">Other Allowance (NGN)</label>
                    <input type="text" value="<?php echo number_format($others, 2)?>" readonly>
                    <input type="hidden" name="other_allow" id="other_allow" value="<?php echo $others?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Gross Pay (NGN)</label>
                    <input type="text" required value="<?php echo number_format($total, 2)?>" style="background:#cdcdcd" readonly>
                    <input type="hidden" name="gross" id="gross" required value="<?php echo $total?>" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Taxable Income (NGN)</label>
                    <input type="text" required value="<?php echo number_format($taxable_income, 2)?>" style="background:#cdcdcd" readonly>
                    <input type="hidden" name="taxable_income" id="taxable_income" required value="<?php echo $taxable_income?>" readonly>
                    <input type="hidden" name="tax_rate" id="tax_rate" required value="<?php echo $rate?>" readonly>
                </div>
            </div>
            <h4 style="margin:5px 0 0 0; font-size:.9rem;">Deductions</h4>
            <hr>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <div class="data" style="width:23%;">
                    <label for="tax">Tax (NGN) - <span style='color:red'><?php echo number_format($rate, 1)?>% Applied</span></label>
                    <input type="number" name="tax" id="tax" required value=<?php echo $tax?> oninput="getNetPay()" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="pension">Pension (NGN) - <span style='color:red'>8% Applied</span></label>
                    <input type="number" name="pension" id="pension" required value=<?php echo $pension?> oninput="getNetPay()" readonly>
                    <input type="hidden" name="employer_contribution" id="employer_contribution" value="<?php echo $employer_contribution?>">
                    <input type="hidden" name="pension_income" id="pension_income" value="<?php echo $pension_income?>">
                </div>
                <div class="data" style="width:23%;">
                    <label for="absence">Absence Penalty (NGN)</label>
                    <input type="number" name="absence" id="absence" required value=<?php echo $absent_fee?> oninput="getNetPay()" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="lateness">Lateness Penalty (NGN)</label>
                    <input type="number" name="lateness" id="lateness" required value=<?php echo $lateness_fee?> oninput="getNetPay()" readonly>
                </div>
                <div class="data" style="width:23%;">
                    <label for="lateness">Loan Repayment (NGN)</label>
                    <input type="number" name="loans" id="loans" required value=0 oninput="getNetPay()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="others">Other Deductions (NGN)</label>
                    <input type="number" name="others" id="others" required value=0 oninput="getNetPay()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Net Pay (NGN)</label>
                    <input type="hidden" name="net_pay" id="net_pay" required value="<?php echo $net_pay?>">
                    <input type="text" name="net_salary" id="net_salary" value="<?php echo number_format($net_pay, 2)?>" required style="background:#cdcdcd" readonly>
                </div>
                <div class="data" style="width:auto">
                    <button type="button" id="add_item" name="add_item" onclick="generatePayroll()">Save record <i class="fas fa-save"></i></button>
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