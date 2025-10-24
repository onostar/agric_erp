<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_SESSION['user_id'])){
        if(isset($_GET['staff'])){
            $staff = $_GET['staff'];
            //get staff details
            $get_details = new selects();
            $rows = $get_details->fetch_details_cond('staffs', 'staff_id', $staff);
            foreach($rows as $row){
                $full_name = $row->last_name." ".$row->other_names;
            }
?>

<div id="salary_structure" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('salary_structure.php')" title="Return to salary structure">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:40%; margin:20px"></div>
    <div class="add_user_form" style="width:80%; margin:0px">
        <h3 style="background:var(--tertiaryColor)">Add Salary Structure for <?php echo $full_name?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="staff" id="staff" value="<?php echo $staff?>">
                <div class="data" style="width:23%">
                    <label for="basic_salary">Basic Salary (NGN)</label>
                    <input type="number" id="basic_salary" name="basic_salary" required value=0 oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                   <label for="housing"> Housing Allow. (NGN)</label>
                   <input type="number" name="housing" id="housing" required value=0 oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="medical">Medical Allow. (NGN)</label>
                    <input type="number" name="medical" id="medical" required value=0 oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="transport">Transport Allow. (NGN)</label>
                    <input type="number" name="transport" id="transport" required value=0 oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="utility">Utility Allowance (NGN)</label>
                    <input type="number" name="utility" id="utility" required value=0 oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="others">Other Allowance (NGN)</label>
                    <input type="number" name="others" id="others" required value=0 oninput="getTotalEarnings()">
                </div>
                <div class="data" style="width:23%;">
                    <label for="total">Total Earnings (NGN)</label>
                    <input type="hidden" name="total" id="total" required value=0 readonly>
                    <input type="text" name="total_pay" id="total_pay" required value=0 style="background:#cdcdcd" readonly>
                </div>
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="addSalary()">Save record <i class="fas fa-save"></i></button>
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