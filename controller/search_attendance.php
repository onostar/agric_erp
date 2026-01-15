<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_users = new selects();
    $details = $get_users->fetch_attendance_date($from, $to, $store);
    $n = 1;
?>
<h2>Attendance Report from '<?php echo date("jS M, Y", strtotime($from)) . "' to '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Attendance report between <?php echo date('d-m-Y', strtotime($from))?> and <?php echo date('d-m-Y', strtotime($to))?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
		        <td>S/N</td>
                <td>Date</td>
                <td>Full Name</td>
                <!-- <td>Staff ID</td> -->
                <td>Department</td>
                <td>Designation</td>
                <td>Time in</td>
                <td>Location</td>
                <td>Marked By</td>
                <td>Time out</td>
                <td>Done By</td>
                <td>Status</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){
        //get staff detail
?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->attendance_date))?></td>
                 <td><?php echo $detail->last_name." ".$detail->other_names?></td>
                <!-- <td><?php echo $detail->staff_id?></td> -->
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
                <td style="color:var(--moreColor)">
                    <?php 
                        if($detail->time_in != NULL){
                            echo date("h:ia", strtotime($detail->time_in));
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->time_in != NULL){
                            $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->marked_by);
                            echo $checkedin_by->full_name;
                        }
                    ?>
                </td>
                <?php if($detail->location == "Head Office"){?>
                <td><?php echo $detail->location?></td>
                <?php }else{?>
                <td><a href="https://www.google.com/maps?q=<?php echo $detail->latitude?>,<?php echo $detail->longitude?>" target="_blank"><?php echo $detail->location?></a></td>
                <?php }?>
                <td style="color:var(--secondaryColor)">
                    <?php 
                        if($detail->time_out != NULL){
                            echo date("h:ia", strtotime($detail->time_out));
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->time_out != NULL){
                             //get done by
                            $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->checked_out_by);
                            echo $checkedin_by->full_name;
                        }
                       
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->attendance_status == "Present"){
                            echo "<span style='color:green'>$detail->attendance_status</span>";
                        }elseif($detail->attendance_status == "Not Signed Out"){
                            echo "<span style='color:var(--tertiaryColor)'>$detail->attendance_status</span>";
                        }elseif($detail->attendance_status == "Absent"){
                            echo "<span style='color:red'>$detail->attendance_status</span>";
                        }elseif($detail->attendance_status == "On Leave"){
                            echo "<span style='color:blue'>$detail->attendance_status</span>";
                        }
                    ?>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
