<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        $staff = htmlspecialchars(stripslashes($_POST['staff']));
        $basic = htmlspecialchars(stripslashes($_POST['basic_salary']));
        $housing = htmlspecialchars(stripslashes($_POST['housing']));
        $medical = htmlspecialchars(stripslashes($_POST['medical']));
        $transport = htmlspecialchars(stripslashes($_POST['transport']));
        $utility = htmlspecialchars(stripslashes($_POST['utility']));
        $others = htmlspecialchars(stripslashes($_POST['others']));
        $total = htmlspecialchars(stripslashes($_POST['total']));
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        $get_details = new selects();
        //get staff details
        $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
        
        //check if staff already has a salary structure
        $check = $get_details->fetch_count_cond('salary_structure', 'staff', $staff);
        if($check > 0){
            echo "<script>alert('Salary structure already set for $full_name')</script>";
            echo "<div class='success'><p style='background:brown'>Salary structure already set for $full_name. <i class='fas fa-thumb-tack'></i></p></div>";
            exit();
        }else{
            $data = array(
                'staff' => $staff,
                'basic_salary' => $basic,
                'medical_allowance' => $medical,
                'housing_allowance' => $housing,
                'transport_allowance' => $transport,
                'utility_allowance' => $utility,
                'other_allowance' => $others,
                'total_earnings' => $total,
                'created_at' => $date,
                'created_by' => $user,
                'store' => $store
            );
            $add_structure = new add_data('salary_structure', $data);
            $add_structure->create_data();
            if($add_structure){
                echo "<div class='success'><p>Salary Structure added for $full_name successfully. <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
        
        
    }else{
        echo "Your Session has expired! Please login again";
    }