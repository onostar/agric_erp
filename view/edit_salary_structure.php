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
?>

<div id="salary_structure" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('salary_structure.php')" title="Return to salary structure">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:40%; margin:20px"></div>
    <div class="add_user_form" style="width:80%; margin:0px">
        <h3 style="background:var(--tertiaryColor)">Update Salary Structure for <?php echo $full_name?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="staff" id="staff" value="<?php echo $staff?>">
                <input type="hidden" name="salary_id" id="salary_id" value="<?php echo $salary_id?>">
                <div class="data" style="width:23%">
                    <label for="basic_salary">Basic Salary (NGN)</label>
                    <input type="number" id="basic_salary" name="basic_salary" required value="<?php echo $basic?>" oninput="getTotalEarnings()" >
                </div>
                <div class="data" style="width:23%;">
                   <label for="housing"> Housing Allow. (NGN)</label>
                   <input type="number" name="housing" id="housing" required value="<?php echo $housing?>" oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="medical">Medical Allow. (NGN)</label>
                    <input type="number" name="medical" id="medical" required value="<?php echo $medical?>" oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="transport">Transport Allow. (NGN)</label>
                    <input type="number" name="transport" id="transport" required value="<?php echo $transport?>" oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="utility">Utility Allowance (NGN)</label>
                    <input type="number" name="utility" id="utility" required value="<?php echo $utility?>" oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="others">Other Allowance (NGN)</label>
                    <input type="number" name="others" id="others" required value="<?php echo $others?>" oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Total Earnings (NGN)</label>
                    <input type="number" name="total" id="total" required value="<?php echo $total?>" style="background:#bdbdbd" readonly>
                </div>
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="updateSalary()">Save record <i class="fas fa-save"></i></button>
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