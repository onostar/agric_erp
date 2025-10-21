<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<style>
    table td{
        font-size:.7rem!important;
        /* padding:2px!important; */
    }
    
</style>

<div class="displays allResults new_data" id="revenue_report" style="margin:0 50px!important;width:90%!important">
    <h2>Leave Requests</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Leave requests')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Full Name</td>
                <td>Staff ID</td>
                <td>Department</td>
                <td>Designation</td>
                <td>Leave Type</td>
                <td>Days</td>
                <td>Requested by</td>
                <td>Date</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2cond('leaves', 'leave_status', 'store', 0, $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get staff detail
                    $rows = $get_users->fetch_details_cond('staffs', 'staff_id', $detail->employee);
                    foreach($rows as $row){
                        $full_name = $row->last_name." ".$row->other_names;
                        $department = $row->department;
                        $designation = $row->designation;
                        $staff_id = $row->staff_number;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $full_name?></td>
                <td><?php echo $staff_id?></td>
                <td>
                    <?php
                        //get department
                        $rows = $get_users->fetch_details_cond('staff_departments', 'department_id', $department);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->department;
                            }
                        }
                        
                    ?>
                </td>
                <td>
                    <?php
                        //get designation
                        $rows = $get_users->fetch_details_cond('designations', 'designation_id', $designation);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->designation;
                            }
                        }
                        
                    ?>
                </td>
                <td>
                    <?php
                        //get leave type
                        $rows = $get_users->fetch_details_cond('leave_types', 'leave_id', $detail->leave_type);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->leave_title;
                            }
                        }
                        
                    ?>
                </td>
                <td style="color:green; text-align:center"><?php echo $detail->total_days?> days</td>
                <td>
                    <?php
                        //get done by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y, h:ia", strtotime($detail->applied))?></td>
                <td>
                    <a style="padding:5px; border-radius:15px;background:var(--otherColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('view_applications.php?id=<?php echo $detail->leaves_id?>')" title="View Application Details">View <i class="fas fa-eye"></i></a>
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
       
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>