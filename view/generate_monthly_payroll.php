<div class="displays allResults" id="payrolls" style="margin:10px 50px!important; width:90%!important">
<?php
session_start();
date_default_timezone_set("Africa/Lagos");
    include "../classes/dbh.php";
    include "../classes/select.php";
    
    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        //get user role
        $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role;
        $store = $_SESSION['store_id'];
        if(isset($_GET['payroll_date'])){
            $pay_date = htmlspecialchars(stripslashes($_GET['payroll_date']));
            $payroll_date = date("Y-m-d", strtotime($pay_date));
            $currentDate = date("Y-m-d");
            if($payroll_date > $currentDate){
                ?>
                <div style='background:#fff;padding:10px; font-size:.8rem; text-align:center; width:30%; margin:auto; border:1px solid #222'>
                    <p style="margin:20px 0">You cannot generate payroll for a future date. Please select a past or current date.</p>
                    <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('generate_payroll.php')" title="Return to payroll">Return <i class="fas fa-angle-double-left"></i></a>

                </div>
                <?php
                exit;
            }else{
?>
   <style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
    <div class="info" style="margin: 10px!important"></div>
    <div class="select_date" style="margin:10px 0!important; padding:0!important">
        <!-- <form method="POST"> -->
        <section style="margin:0!important; padding:0!important">    
            <div class="from_to_date" style="width:auto; margin:0!important; padding:0">
                <label>Select Date</label><br>
                <input type="date"name="payroll_month" id="payroll_month" value="<?php echo date("Y-m-d", strtotime($payroll_date))?>"onchange="showPage('generate_monthly_payroll.php?payroll_date='+this.value)">
            </div>
           
        </section>
    </div>
    <!-- showing staffs that are not resigned in selected store -->
    <h2>Generate Staff Payroll for <?php echo date("F, Y", strtotime($payroll_date))?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'List of Active Staffs')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Staff</td>
                <td>Staff ID</td>
                <td>Gender</td>
                <!-- <td>Department</td> -->
                <td>Designation</td>
                <td>Basic Salary</td>
                <td>Gross Pay</td>
                <td>Status</td>
                <td></td>
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_generate_payrollpermonth($store, $payroll_date);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->last_name ." ". $detail->other_names?></td>
                <td><?php echo $detail->staff_number?></td>
                
                <td><?php echo $detail->gender?></td>
                <!--<td>
                    <?php
                        //get sponsor
                       /*  $get_reg = new selects();
                        $rows = $get_reg->fetch_details_cond('staff_departments', 'department_id', $detail->department);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->department;
                            }
                        } */
                        
                    ?>
                </td>-->
                <td>
                    <?php
                        //get sponsor
                        $get_reg = new selects();
                        $rows = $get_reg->fetch_details_cond('designations', 'designation_id', $detail->designation);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->designation;
                            }
                        }
                        
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->basic_salary, 2)?></td>
                <td style="color:green"><?php echo "₦".number_format($detail->total_earnings, 2)?></td>
                <td><?php echo $detail->payroll_status?></td>
                <td>
                    <?php if($detail->payroll_status == 'Pending'){?>
                    <a style="padding:5px 8px; border-radius:15px;background:var(--tertiaryColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('get_staff_payroll_date.php?staff=<?php echo $detail->staff_id?>&salary_id=<?php echo $detail->salary_id?>&payroll_date=<?php echo $payroll_date?>')" title="generate staff payroll">Generate <i class="fas fa-hand-holding-dollar"></i></a>
                    <?php }elseif($detail->payroll_status == 'Generated'){?>
                    <a style="padding:5px 8px; border-radius:15px;background:var(--otherColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="showPage('view_staff_payroll.php?payroll=<?php echo $detail->payroll_id?>')" title="View Payroll">View <i class="fas fa-eye"></i></a>
                    <?php }else{?>
                    <a style="padding:5px 8px; border-radius:15px;background:brown;color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="showPage('add_salary_structure.php?staff=<?php echo $detail->staff_id?>')" title="Create salary structure">Create <i class="fas fa-user-plus"></i></a>
                    <?php }
                        if($detail->payroll_stat == 1){
                    ?>
                    <a style="padding:5px 8px; border-radius:15px;background:green;color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="printPaySlip('<?php echo $detail->payroll_id?>')" title="Print Pay Slip">Print pay slip <i class="fas fa-print"></i></a>
                    <?php }?>
                </td>
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    
    ?>
<?php 
            }
        }else{
            echo "Please Select a date to continue";
        }
    }else{
        echo "Your session has expired! Please login again to continue";
    }    
?>
</div>