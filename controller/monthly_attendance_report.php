<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        if(isset($_GET['month'])){
            $attendance_date = htmlspecialchars(stripslashes($_GET['month']));
            include "../classes/dbh.php";
            include "../classes/select.php";

?>
    <h2>Staff Attendance Report for <?php echo date("F, Y", strtotime($attendance_date))?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Staff Attendance report for <?php echo date('F, Y', strtotime($attendance_date))?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
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
        <tbody id="result">
        <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_condOrder('staffs', 'store', $store, 'last_name');
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
                        $rows = $get_items->fetch_details_cond('staff_departments', 'department_id', $detail->department);
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
                        $rows = $get_items->fetch_details_cond('designations', 'designation_id', $detail->designation);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->designation;
                            }
                        }
                        
                    ?>
                </td>
                <td style="color:var(--tertiaryColor); text-align:center">
                    <?php 
                       $work_days = $get_items->fetch_staff_work_days_month($detail->staff_id, $attendance_date);
                       echo $work_days;
                    ?>
                </td>
                <td style="color:var(--moreColor); text-align:center">
                    <?php 
                       $late_days = $get_items->fetch_late_days_month($detail->staff_id, $attendance_date);
                       echo $late_days;
                    ?>
                </td>
                
                <td>
                    <a href="javascript:void(0)" onclick="showPage('view_staff_attendance.php?staff=<?php echo $detail->staff_id?>&attendance_date=<?php echo date('d-m-Y', strtotime($attendance_date))?>')" style="background:var(--tertiaryColor); color:#fff; padding:4px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222" title="view attendance">View <i class="fas fa-eye"></i></a>
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
        echo "Your session has expired! Pleaselogin again to continue";
        exit;
    }