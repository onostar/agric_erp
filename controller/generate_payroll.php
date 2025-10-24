<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $payroll_date = date("Y-m-d");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $gross = htmlspecialchars(stripslashes($_POST['gross']));
        $tax = htmlspecialchars(stripslashes($_POST['tax']));
        $pension = htmlspecialchars(stripslashes($_POST['pension']));
        $absence = htmlspecialchars(stripslashes($_POST['absence']));
        $lateness = htmlspecialchars(stripslashes($_POST['lateness']));
        $loans = htmlspecialchars(stripslashes($_POST['loans']));
        $taxable_income = htmlspecialchars(stripslashes($_POST['taxable_income']));
        $employer_contribution = htmlspecialchars(stripslashes($_POST['employer_contribution']));
        $others = htmlspecialchars(stripslashes($_POST['others']));
        $net_pay = htmlspecialchars(stripslashes($_POST['net_pay']));
        $pension_income = htmlspecialchars(stripslashes($_POST['pension_income']));
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
        $check = $get_details->fetch_count_curMonth('salary_structure', 'payroll_date', 'staff', $staff);
        if($check > 0){
            echo "<script>alert('Payroll already generated for $full_name this month')</script>";
            echo "<div class='success'><p style='background:brown'>Payroll already generated for $full_name this month. <i class='fas fa-thumb-tack'></i></p></div>";
            exit();
        }else{
            $data = array(
                'payroll_date' => $payroll_date,
                'staff' => $staff,
                'gross_pay' => $gross,
                'tax' => $tax,
                'pension' => $pension,
                'absence_penalty' => $absence,
                'lateness_ppenalty' => $lateness,
                'loan_repayment' => $loans,
                'other_deductions' => $others,
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
                }
                   
                echo "<div class='success'><p>Payroll generated successfully for  $full_name. <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }