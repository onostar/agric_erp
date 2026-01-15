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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_flagged_attendance.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Attendance Flagged Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Flagged Attendance report for <?php echo date('d-m-Y')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Full Name</td>
                <!-- <td>Staff ID</td> -->
                <td>Department</td>
                <td>Designation</td>
                <td>Time in</td>
                <td>Location</td>
                <td>Flagged By</td>
                <td>Time</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('attendance_flagging', 'post_date', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
//get name
                        $names = $get_users->fetch_details_cond('staffs', 'staff_id', $detail->staff);
                        foreach($names as $name){
                            $full_name = $name->last_name." ".$name->other_names;
                            $department = $name->department;
                            $designation = $name->designation;
                        }
                  
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        
                        echo $full_name;
                        ?>
                </td>
                <td>
                    <?php
                        
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
                <td style="color:var(--moreColor)">
                    <?php 
                       
                        echo date("h:ia", strtotime($detail->time_in));
                        
                    ?>
                </td>
               
                <td><a href="https://www.google.com/maps?q=<?php echo $detail->latitude?>,<?php echo $detail->longitude?>" target="_blank"><?php echo $detail->location?></a></td>
                
                <td>
                    <?php
                            $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->flagged_by);
                            echo $checkedin_by->full_name;
                        
                    ?>
                </td>
                <td><?php echo date("H:ia",strtotime($detail->post_date))?></td>

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