<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateConOrder('leaves', 'store', 'date(applied)', $from, $to, $store, 'applied');
    $n = 1;
?>
<h2>Staff Leave Report between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Leave report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
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
                <td>Applied</td>
                <td>Status</td>
                <td>Applied By</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){
        //get staff detail
        $rows = $get_revenue->fetch_details_cond('staffs', 'staff_id', $detail->employee);
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
                        $rows = $get_revenue->fetch_details_cond('staff_departments', 'department_id', $department);
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
                        $rows = $get_revenue->fetch_details_cond('designations', 'designation_id', $designation);
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
                        $rows = $get_revenue->fetch_details_cond('leave_types', 'leave_id', $detail->leave_type);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                echo $row->leave_title;
                            }
                        }
                        
                    ?>
                </td>
                <td>
                    <?php echo date("h:i:sa",strtotime($detail->applied))?>
                </td>
                <td>
                    <?php
                        if($detail->leave_status == 0){
                            echo "<span style='color:var(--moreColor)'>Pending</span>";
                        }elseif($detail->leave_status == 1){
                            echo "<span style='color:var(--tertiaryColor)'>Approved</span>";
                        }elseif($detail->leave_status == -1){
                            echo "<span style='color:var(--secondaryColor)'>Declined</span>";
                        }else{
                            echo "<span style='color:var(--otherColor)'>Returned</span>";
                        }
                    ?>
                </td>
                <td>
                    <?php
                        //get done by
                        $checkedin_by = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="padding:5px; border-radius:15px;background:var(--tertiaryColor);color:#fff; box-shadow:1px 1px 1px #222; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('leave_details.php?id=<?php echo $detail->leaves_id?>&staff=<?php echo $detail->employee?>')" title="View leave Details">View <i class="fas fa-eye"></i></a>
                    
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
