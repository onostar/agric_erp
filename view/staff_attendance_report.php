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
<div id="attendanceReport" class="displays management" style="margin:0!important;width:100%!important">
    <?php
        $get_users = new selects();

    ?>
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            
            <div class="from_to_date">
                <label>Select Month</label><br>
                <select name="months" id="months" onchange="searchMonthlyAttendance(this.value)">
                    <option value="<?php echo date("Y-m-d")?>" selected disabled><?php echo date("F, Y")?></option>
                    <?php
                        //get all payrolls groupd by month
                        $results = $get_users->fetch_monthly_lateness($store);
                        if(!empty($results)){
                            foreach($results as $result){
                    ?>
                    <option value="<?php echo date("Y-m-d", strtotime($result->attendance_date))?>"><?php echo date("F, Y", strtotime($result->attendance_date))?></option>
                    <?php }}?>
                </select>
            </div>
            
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Staff Attendance Report for <?php echo date("F, Y")?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Staff Attendance report for <?php echo date('F, Y')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Full Name</td>
                <td>Staff ID</td>
                <td>Department</td>
                <td>Designation</td>
                <td>Days Present</td>
                <td>Late Count</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $details = $get_users->fetch_details_condOrder('staffs', 'store', $store, 'last_name');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                  
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->last_name." ".$detail->other_names?></td>
                <td><?php echo $detail->staff_id?></td>
                <td>
                    <?php
                        //get department
                        $rows = $get_users->fetch_details_cond('staff_departments', 'department_id', $detail->department);
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
                        $rows = $get_users->fetch_details_cond('designations', 'designation_id', $detail->designation);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->designation;
                            }
                        }
                        
                    ?>
                </td>
                <td style="color:var(--tertiaryColor); text-align:center">
                    <?php 
                       $work_days = $get_users->fetch_staff_work_days($detail->staff_id);
                       echo $work_days;
                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                       $late_days = $get_users->fetch_late_days($detail->staff_id);
                       echo $late_days;
                    ?>
                </td>
                
                <td>
                    <a href="javascript:void(0)" onclick="showPage('view_staff_attendance.php?staff=<?php echo $detail->staff_id?>&attendance_date=<?php echo date('d-m-Y')?>')" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" title="view attendance">View <i class="fas fa-eye"></i></a>
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