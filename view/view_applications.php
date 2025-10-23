<div id="leave_details">
<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $today = date("Y-m-d");
        if(isset($_GET['id']) && isset($_GET['staff'])){
            $customer = $_GET['staff'];
            $leave_id = $_GET['id'];
            //get customer name
            $get_customer = new selects();
            //get leave details
            $details = $get_customer->fetch_details_cond('leaves', 'leaves_id', $leave_id);
            foreach($details as $detail){
                $leave_type = $detail->leave_type;
                $max_days = $detail->max_days;
                $start = $detail->start_date;
                $end = $detail->end_date;
                $total_days = $detail->total_days;
                $reason = $detail->reason;
            }
            $rows = $get_customer->fetch_details_cond('staffs', 'staff_id', $customer);
            foreach($rows as $row){

?>
<style>
    .nomenclature .inputs{
    width:100%;
 }
 .nomenclature .inputs .data{
    width:32%;
 }
 .nomenclature .inputs .data label{
    width:30%;
    color:#222;
 }
 #main_consult textarea{
    width:100%;
    height:50px;
    padding:5px;
 }
 #main_consult label{
    background:var(--otherColor)!important;
    text-align:left!important;
    /* color:#222!important; */
 }
</style>
<a style="border-radius:15px; background:brown;color:#fff;padding:6px; box-shadow:1px 1px 1px #222; position:fixed; border:1px solid #fff; "href="javascript:void(0)" onclick="showPage('approve_leave.php')"><i class="fas fa-close"></i> Close</a>
    <div id="patient_details">
        <h3 style="background:var(--labColor); color:#fff">Leave Request Details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="nomenclature">
            <!-- <div class="profile_foto">
                <img src="<?php echo '../photos/'.$row->photo?>" alt="Photo">
            </div> -->
            <div class="inputs">
                <div class="data">
                    <label for="customer">Full Name:</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo $row->last_name." ".$row->other_names?>" readonly>
                </div>
              
                <div class="data">
                    <label for="prn">Staff ID:</label>
                    <input type="text" name="prn" id="prn" value="<?php echo $row->staff_number?>" readonly>
                   
                </div>
                <div class="data">
                    <label for="phone_number">Phone number:</label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="0033421100" required value="<?php echo $row->phone?>" readonly>
                </div>
                <div class="data">
                    <label for="email">Email address:</label>
                    <input type="text" name="email" id="email" required value="<?php echo $row->email_address?>" readonly>
                </div>
                <div class="data">
                    <label for="customer_store">Date of birth:</label>
                    <?php
                        $date = new DateTime($row->dob);
                        $now = new DateTime();
                        $interval = $now->diff($date);
                    
                    ?>
                    <input type="text" value="<?php echo date("Y-m-d", strtotime($row->dob))." (".$interval->y."years)";?>">
                </div>
                <div class="data">
                    <label for="gender">Gender:</label>
                    <input type="text" value="<?php echo $row->gender?>">
                </div>
                <div class="data">
                    <label for="Address">Address:</label>
                    <input type="text" value="<?php echo $row->home_address?>" readonly>
                </div>
                <div class="data">
                    <label for="customer_store">Employed:</label>
                    <input type="text" value="<?php echo date("Y-m-d", strtotime($row->employed));?>">
                </div>
                <div class="data">
                    <label for="category">Category:</label>
                    <input type="text" value="<?php echo $row->staff_category?>" readonly>
                </div>
                <div class="data">
                    <label for="ailment">Department:</label>
                        <?php
                              $get_reg = new selects();
                              $details = $get_reg->fetch_details_cond('staff_departments', 'department_id', $row->department);
                              if(gettype($details) == 'array'){
                                  foreach($details as $detail){
                                      $department_name = $detail->department;
                                  }
                              }
                              if(gettype($details) == "string"){
                                $department_name = "";
                              }
                        ?>
                        <input type="text" value="<?php echo $department_name?>" readonly>
                </div>
                <div class="data">
                    <label for="ailment">Designation:</label>
                        <?php
                              $get_reg = new selects();
                              $details = $get_reg->fetch_details_cond('designations', 'designation_id', $row->designation);
                              if(gettype($details) == 'array'){
                                  foreach($details as $detail){
                                      $designation_name = $detail->designation;
                                  }
                              }
                              if(gettype($details) == "string"){
                                $designation_name = "";
                              }
                              
                        ?>
                        <input type="text" value="<?php echo $designation_name?>" readonly>
                </div>
                
            </div>
        </section>    
        <section id="main_consult">
            <h3 style="background:var(--labColor); text-align:left">Leave Application Details</h3>
            
            <form style="padding:0!important; margin:0!important">
                <div class="inputs" style="margin:0!important; padding:10px 0!important">
                    <div class="data" style="width:30%!important; margin-top:0!important">
                        <?php
                            //get leave type
                            $types = $get_customer->fetch_details_group('leave_types', 'leave_title', 'leave_id', $leave_type);
                            $leave_title = $types->leave_title;
                        ?>
                        <label for="notes" style="text-align:left">Leave Type</label>
                        <input type="text" readonly value="<?php echo $leave_title?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Maximum Days Allowed</label>
                        <input type="text" readonly value="<?php echo $max_days?> days">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Start Date</label>
                        <input type="text" readonly value="<?php echo date("jS M, Y", strtotime($start))?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">End Date</label>
                        <input type="text" readonly value="<?php echo date("jS M, Y", strtotime($end))?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Total Days</label>
                        <input type="text" readonly value="<?php echo $total_days?> days">
                    </div>
                    <div class="data" style="width:50%!important">
                        <label for="notes">Reason for Leave</label>
                        <textarea name="note" id="note" readonly style="min-height:100px"><?php echo $reason?></textarea>
                    </div>
                    
                    
                </div>
            </form>
                
            
        </section>
        <section id="last_consult">
            <h3>Previous Leave Requests</h3>
            <div class="displays allResults new_data" style="width:100%!important;margin:0!important">
                <table id="data_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--moreColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Leave Type</td>
                            <td>Started</td>
                            <td>End Date</td>
                            <td>Status</td>
                            <td>Approved By</td>
                            <td>Returned</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $get_users = new selects();
                            $results = $get_users->fetch_details_negCond('leaves', 'leaves_id', $leave_id, 'employee', $customer);
                            if(gettype($results) === 'array'){
                            foreach($results as $result):
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td style="color:var(--moreColor)"><?php echo date("d-m-Y h:ia", strtotime($result->applied));?></td>
                            <td>
                                <?php
                                    $lvs = $get_users->fetch_details_group('leave_types', 'leave_title', 'leave_id', $result->leave_type) ;
                                    echo $lvs->leave_title 
                                ?>
                                </td>
                            <td><?php echo date("d-M-Y", strtotime($result->start_date));?></td>
                            <td><?php echo date("d-M-Y", strtotime($result->end_date));?></td>
                            <td>
                                <?php
                                    if($result->leave_status == -1){
                                        echo "<span style='color:red'>Rejected</span>";
                                    }else{
                                        echo "<span style='color:green'>Approved</span>";
                                    }
                                ?>
                            </td>
                             <td>
                                <?php
                                    
                                    //get posted by
                                    $checks = $get_customer->fetch_details_cond('users',  'user_id', $result->approved_by);
                                    foreach($checks as $check){
                                        $full_name = $check->full_name;
                                    }
                                    echo $full_name;
                                ?>
                            </td>
                            <td>
                                <?php
                                    if($result->leave_status == 2){
                                        echo date("d-M-Y", strtotime($result->returned));
                                    }else{
                                        "";
                                    }
                                ?>
                            </td>
                           
                            
                        </tr>
                        <?php $n++; endforeach;}?>
                    </tbody>
                </table>
                <?php
                    if(gettype($results) == 'string'){
                        echo "<p class='not_result'; style='text-align:center;font-size:.9rem;'>$results</p>";
                    }
                ?>
            </div>
        </section>
        <section id="last_consult">
            <form>
                <div class="inputs">
                    <button type="button" onclick="approveLeave('<?php echo $leave_id?>')" style="color:#fff; background:green; border:1px solid #fff; border-radius: 15px; box-shadow: 1px 1px 1px #222; padding:5px; margin:5px;">Approve <i class="fas fa-check"></i></button>
                    <button type="button" onclick="declineLeave('<?php echo $leave_id?>')" style="color:#fff; background:brown; border:1px solid #fff; border-radius: 15px; box-shadow: 1px 1px 1px #222; padding:5px; margin:5px;">Decline <i class="fas fa-close"></i></button>
                </div>
            </form>
        </section>
    </div>

<?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>
