<div class="displays allResults" id="attendance" style="margin:10px 50px!important; width:90%!important">
<?php
session_start();
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
        font-size:.7rem!important;
        /* padding:2px!important; */
    }
    
</style>
    
    <div class="info" style="margin: 10px!important"></div>
    <!-- showing staffs that are not resigned in selected store -->
    <h2>Staff Salary Structure</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'List of Active Staffs')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Staff</td>
                <td>Staff ID</td>
                <td>Gender</td>
                <!-- <td>Department</td> -->
                <td>Designation</td>
                <td>Basic Salary</td>
                <td>Housing</td>
                <td>Transport</td>
                <td>Medical</td>
                <td>Utility</td>
                <td>Other Allowance</td>
                <!-- <td>Total Earning</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_salary_structure($store);
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
                <td style="color:green"><?php echo "₦".number_format($detail->basic_salary, 2)?></td>
                <td><?php echo "₦".number_format($detail->housing_allowance, 2)?></td>
                <td><?php echo "₦".number_format($detail->transport_allowance, 2)?></td>
                <td><?php echo "₦".number_format($detail->medical_allowance, 2)?></td>
                <td><?php echo "₦".number_format($detail->utility_allowance, 2)?></td>
                <td><?php echo "₦".number_format($detail->other_allowance, 2)?></td>
                <td>
                    <?php if(empty($detail->basic_salary)){?>
                    <a style="padding:5px 8px; border-radius:5px;background:var(--tertiaryColor);color:#fff;" href="javascript:void(0)" onclick="showPage('add_salary_structure.php?staff=<?php echo $detail->staff_id?>')" title="Add Salary Structure"><i class="fas fa-plus-square"></i></a>
                    <?php }else{?>
                    <a style="padding:5px 8px; border-radius:5px;background:var(--otherColor);color:#fff;" href="javascript:void(0)" onclick="showPage('edit_salary_structure.php?staff=<?php echo $detail->staff_id?>')" title="Update salary structure"><i class="fas fa-edit"></i></a>
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