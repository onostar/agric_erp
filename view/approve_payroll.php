<div class="displays allResults" id="payroll" style="margin:10px 50px!important; width:90%!important">
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
    <h2>Approve Staff Payroll for <?php echo date("F, Y")?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Payroll for <?php echo date('F, Y')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
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
                <td>Net Pay</td>
                <td>Prepared By</td>
                <td>Date</td>
                <td></td>
            </tr>
        </thead>
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_curmonth2Con('payroll', 'payroll_date', 'store', $store, 'payroll_status', 0);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get staff name
                        $rows = $get_items->fetch_details_cond('staffs','staff_id', $detail->staff);
                        foreach($rows as $row){
                            $full_name = $row->title." ".$row->last_name." ".$row->other_names;
                            $gender = $row->gender;
                            $staff_id = $row->staff_number;
                            $designation = $row->designation;
                        }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        
                        echo $full_name?></td>
                <td><?php echo $staff_id?></td>
                
                <td><?php echo $gender?></td>
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
                        //get designation
                        $desg = $get_items->fetch_details_cond('designations', 'designation_id', $designation);
                        if(gettype($desg) == 'array'){
                            foreach($desg as $des){
                                echo $des->designation;
                            }
                        }
                        
                    ?>
                </td>
                <td style="color:var(--tertiaryColor)"><?php echo "₦".number_format($detail->net_pay, 2)?></td>
                <td>
                    <?php
                        //get prepared by
                        $prs = $get_items->fetch_details_group('users', 'full_name', 'user_id', $detail->prepared_by);
                        echo $prs->full_name;
                    ?>
                </td>
                <td style="color:var(--primaryColor)"><?php echo date("d-M-Y", strtotime($detail->date_generated))?></td>
                <td>
                    <a style="padding:5px 8px; border-radius:15px;background:var(--tertiaryColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('payroll_details.php?payroll=<?php echo $detail->payroll_id?>')" title="View details">View <i class="fas fa-eye"></i></a>
                   
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