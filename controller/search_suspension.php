<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateConOrder('suspensions', 'store', 'date(suspension_date)', $from, $to, $store, 'suspension_date');
    $n = 1;
?>
<h2>Staff Suspensions between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Staff Suspension report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
		        <td>S/N</td>
                <td>Date</td>
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
    if(gettype($details) === 'array'){
    foreach($details as $detail){
        //get staff detail
        $rows = $get_revenue->fetch_details_cond('staffs', 'staff_id', $detail->staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
            $department = $row->department;
            $designation = $row->designation;
            $staff_id = $row->staff_number;
        }
?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y, H:ia", strtotime($detail->suspension_date))?></td>
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
                        //get done by
                        $checkedin_by = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->suspended_by);
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
                             $checkedin_by = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->recalled_by);
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
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
