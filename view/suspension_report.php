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
<div id="revenueReport" class="displays management" style="margin:0!important;width:100%!important">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_suspension.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Suspension Reports for Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Suspension report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Time</td>
                <td>Full Name</td>
                <td>Staff ID</td>
                <td>Department</td>
                <td>Designation</td>
                <td>Done by</td>
                <td>Status</td>
                <td>Recalled By</td>
                <td>Date Recalled</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('suspensions', 'suspension_date', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get staff detail
                    $rows = $get_users->fetch_details_cond('staffs', 'staff_id', $detail->staff);
                    foreach($rows as $row){
                        $full_name = $row->last_name." ".$row->other_names;
                        $department = $row->department;
                        $designation = $row->designation;
                        $staff_id = $row->staff_number;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:ia", strtotime($detail->suspension_date))?></td>
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
                        //get done by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->suspended_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->suspension_status == 0){
                            echo "<span style='color:red'>Active</span>";
                        }else{
                            echo "<span style='color:green'>Recalled</span>";
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->suspension_status == 1){
                             $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->recalled_by);
                            echo $checkedin_by->full_name;
                        }else{
                            echo "Nil";
                        }
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php
                        if($detail->suspension_status == 1){
                            echo date("d-M-Y, H:ia", strtotime($detail->recall_date));
                        }else{
                            echo "Nil";
                        }
                    ?>
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