<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['staff']) && isset($_GET['attendance_date'])){
        $staff = $_GET['staff'];
        $attendance_date = $_GET['attendance_date'];

        //get staff details
        $get_users = new selects();
        $rows = $get_users->fetch_details_cond('staffs', 'staff_id', $staff);
        foreach($rows as $row){
            $full_name = $row->last_name." ".$row->other_names;
        }
    

?>
<style>
    table td{
        font-size:.7rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="attendanceReport" class="displays management" style="margin:0!important;width:100%!important">
    <a href="javascript:void(0)" onclick="showPage('staff_attendance_report.php')" style="background:brown; color:#fff; padding:5px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222; margin:0 50px" title="Return">Return <i class="fas fa-angle-double-left"></i></a>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Showing <?php echo date("F, Y", strtotime($attendance_date))?> Attendance report for <?php echo $full_name?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', '<?php echo date('F, Y',strtotime($attendance_date))?> Attendance report for <?php echo $full_name?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Date</td>
                <td>Time in</td>
                <td>Marked By</td>
                <td>Location</td>
                <td>Time out</td>
                <td>Done By</td>
                <!-- <td>Status</td> -->
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_individual_attendance($staff, $attendance_date);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo date("d-M-Y", strtotime($detail->attendance_date))?></td>
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
                <?php if($detail->location == "Head Office" || $detail->time_in == NULL){?>
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
                <!-- <td>
                    <?php
                        /* if($detail->status == "Present"){
                            echo "<span style='color:green'>$detail->status</span>";
                        }elseif($detail->status == "Not Signed Out"){
                            echo "<span style='color:var(--tertiaryColor)'>At Work</span>";
                        }elseif($detail->status == "Absent"){
                            echo "<span style='color:red'>$detail->status</span>";
                        }elseif($detail->status == "On Leave"){
                            echo "<span style='color:blue'>$detail->status</span>";
                        } */
                    ?>
                </td> -->
                

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
<?php }?>
<script src="../jquery.js"></script>
<script src="../script.js"></script>