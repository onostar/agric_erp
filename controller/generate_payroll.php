<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $payroll_date =  htmlspecialchars(stripslashes($_POST['payroll_date']));
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $working_days = htmlspecialchars(stripslashes($_POST['working_days']));
        $days_at_work = htmlspecialchars(stripslashes($_POST['days_at_work']));
        $leave_days = htmlspecialchars(stripslashes($_POST['leave_days']));
        $absent_days = htmlspecialchars(stripslashes($_POST['absent_days']));
        $suspension_days = htmlspecialchars(stripslashes($_POST['suspension_days']));
        $late_days = htmlspecialchars(stripslashes($_POST['late_days']));
        $basic_salary = htmlspecialchars(stripslashes($_POST['basic_salary']));
        $housing = htmlspecialchars(stripslashes($_POST['housing']));
        $medical = htmlspecialchars(stripslashes($_POST['medical']));
        $transport = htmlspecialchars(stripslashes($_POST['transport']));
        $utility = htmlspecialchars(stripslashes($_POST['utility']));
        $other_allow = htmlspecialchars(stripslashes($_POST['other_allow']));
        $gross = htmlspecialchars(stripslashes($_POST['gross']));
        $tax = htmlspecialchars(stripslashes($_POST['tax']));
        $pension = htmlspecialchars(stripslashes($_POST['pension']));
        $absence = htmlspecialchars(stripslashes($_POST['absence']));
        // $lateness = htmlspecialchars(stripslashes($_POST['lateness']));
        $loans = htmlspecialchars(stripslashes($_POST['loans']));
        $taxable_income = htmlspecialchars(stripslashes($_POST['taxable_income']));
        $tax_rate = htmlspecialchars(stripslashes($_POST['tax_rate']));
        $employer_contribution = htmlspecialchars(stripslashes($_POST['employer_contribution']));
        $others = htmlspecialchars(stripslashes($_POST['others']));
        $net_pay = htmlspecialchars(stripslashes($_POST['net_pay']));
        $pension_income = htmlspecialchars(stripslashes($_POST['pension_income']));
        $daily_pay = htmlspecialchars(stripslashes($_POST['daily_pay']));
        $net_after_tax = htmlspecialchars(stripslashes($_POST['net_after_tax']));
        $total_contribution = $pension + $employer_contribution;

        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        
        //check if staff already has payroll for this month
        $check = $get_details->fetch_count_specificMonth('payroll', 'payroll_date', $payroll_date, 'staff', $staff);
        if($check > 0){
            echo "<script>alert('Payroll already generated for $full_name this month')</script>";
            echo "<div class='success'><p style='background:brown'>Payroll already generated for $full_name this month. <i class='fas fa-thumb-tack'></i></p></div>";
            exit();
        }else{
            $data = array(
                'payroll_date' => $payroll_date,
                'staff' => $staff,
                'working_days' => $working_days,
                'days_at_work' => $days_at_work,
                'leave_days' => $leave_days,
                'suspension_days' => $suspension_days,
                'absent_days' => $absent_days,
                'late_days' => $late_days,
                'basic_salary' => $basic_salary,
                'housing' => $housing,
                'medical' => $medical,
                'transport' => $transport,
                'utility' => $utility,
                'other_allowance' => $other_allow,
                'gross_pay' => $gross,
                'tax' => $tax,
                'tax_rate' => $tax_rate,
                'taxable_income' => $taxable_income,
                'pension' => $pension,
                'absence_penalty' => $absence,
                // 'lateness_penalty' => $lateness,
                'loan_repayment' => $loans,
                'other_deductions' => $others,
                'daily_pay' => $daily_pay,
                'net_after_tax' => $net_after_tax,
                'net_pay' => $net_pay,
                'date_generated' => $date,
                'prepared_by' => $user,
                'store' => $store
            );
            $add_payroll = new add_data('payroll', $data);
            $add_payroll->create_data();
            if($add_payroll){
                //get payroll id
                $ids = $get_details->fetch_lastInserted('payroll', 'payroll_id');
                $id = $ids->payroll_id;
                //check if pension was recorded
                if(floatval($pension) > 0){
                    //insert into pension contribution table
                    $pension_data = array(
                        'payroll_id' => $id,
                        'staff' => $staff,
                        'store' => $store,
                        'pensionable_income' => $pension_income,
                        'employee_contribution' => $pension,
                        'employer_contribution' => $employer_contribution,
                        'total_contribution' => $total_contribution,
                        'payroll_date' => date("Y-m-d"),
                        'posted_by' => $user,
                        'post_date' => $date
                    );
                    $add_pension = new add_data('pensions', $pension_data);
                    $add_pension->create_data();
                }
                   
                echo "<div class='success'><p>Payroll generated successfully for  $full_name. <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }