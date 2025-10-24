<div class="displays allResults" id="attendance" style="margin:10px 50px!important; width:90%!important">
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
        

?>
   <style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
    
    <div class="info" style="margin: 10px!important"></div>
    <!-- showing staffs that are not resigned in selected store -->
    <h2>Generate Staff Payroll for <?php echo date("F, Y")?></h2>
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
                <td>Total</td>
                <td>Status</td>
                <td></td>
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_generate_payroll($store);
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
                    <a style="padding:5px 8px; border-radius:15px;background:var(--tertiaryColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('get_staff_payroll.php?staff=<?php echo $detail->staff_id?>&salary_id=<?php echo $detail->salary_id?>')" title="generate staff payroll">Generate <i class="fas fa-hand-holding-dollar"></i></a>
                    <?php }elseif($detail->payroll_status == 'Generated'){?>
                    <a style="padding:5px 8px; border-radius:15px;background:var(--otherColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="showPage('view_staff_payroll.php?staff=<?php echo $detail->staff_id?>')" title="View Payroll">View <i class="fas fa-eye"></i></a>
                    <?php }else{?>
                    <a style="padding:5px 8px; border-radius:15px;background:brown;color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff;" href="javascript:void(0)" onclick="showPage('add_salary_structure.php?staff=<?php echo $detail->staff_id?>')" title="View Payroll">Create <i class="fas fa-user-plus"></i></a>
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
    }else{
        echo "Your session has expired! Please login again to continue";
    }    
?>
</div>