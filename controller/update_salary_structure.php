<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $salary_id = htmlspecialchars(stripslashes($_POST['salary_id']));
        $basic = htmlspecialchars(stripslashes($_POST['basic_salary']));
        $housing = htmlspecialchars(stripslashes($_POST['housing']));
        $medical = htmlspecialchars(stripslashes($_POST['medical']));
        $transport = htmlspecialchars(stripslashes($_POST['transport']));
        $utility = htmlspecialchars(stripslashes($_POST['utility']));
        $others = htmlspecialchars(stripslashes($_POST['others']));
        $total = htmlspecialchars(stripslashes($_POST['total']));
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        
        $data = array(
            'basic_salary' => $basic,
            'medical_allowance' => $medical,
            'housing_allowance' => $housing,
            'transport_allowance' => $transport,
            'utility_allowance' => $utility,
            'other_allowance' => $others,
            'total_earnings' => $total,
            'updated_at' => $date,
            'updated_by' => $user,
        );
        $update = new Update_table();
        $update->updateAny('salary_structure', $data, 'salary_id', $salary_id);
        if($update){
            echo "<div class='success'><p>Salary Structure updated for $full_name successfully. <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }else{
        echo "Your Session has expired! Please login again";
    }