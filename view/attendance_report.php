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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_attendance.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Attendance Reports for Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Attendance report for <?php echo date('d-m-Y')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
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
                <td>Marked By</td>
                <td>Location</td>
                <td>Time out</td>
                <td>Done By</td>
                <td>Status</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_attendance($store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                  
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
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
                        if($detail->status == "Present"){
                            echo "<span style='color:green'>$detail->status</span>";
                        }elseif($detail->status == "Still Present"){
                            echo "<span style='color:var(--tertiaryColor)'>At Work</span>";
                        }elseif($detail->status == "Absent"){
                            echo "<span style='color:red'>$detail->status</span>";
                        }elseif($detail->status == "On Leave"){
                            echo "<span style='color:blue'>$detail->status</span>";
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