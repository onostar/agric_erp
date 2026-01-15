<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_users = new selects();
    $details = $get_users->fetch_details_2dateConOrder('attendance_flagging', 'store', 'date(post_date)', $from, $to, $store, 'post_date');
    $n = 1;
?>
<h2>Attendance Flagged between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Flagged Attendance report between <?php echo date('d-m-Y', strtotime($from))?> and <?php echo date('d-m-Y', strtotime($to))?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
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
                <td>Date</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){
        //get staff detail
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
                <td><?php echo date("d-M-Y, H:ia",strtotime($detail->post_date))?></td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
