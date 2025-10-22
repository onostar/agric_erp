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
                $status = $detail->leave_status;
                $approved_by = $detail->approved_by;
                $approved = $detail->approved_at;
                $returned = $detail->returned;
                $ended_by = $detail->ended_by;
            }
            //get approved by details
            $aps = $get_customer->fetch_details_group('users', 'full_name', 'user_id', $approved_by);
            $approval = $aps->full_name;
            //get returned  by details
            $aps = $get_customer->fetch_details_group('users', 'full_name', 'user_id', $ended_by);
            $ender = $aps->full_name;
            //get leave title
            $ttls = $get_customer->fetch_details_group('leave_types', 'leave_title', 'leave_id', $leave_type);
            $title = $ttls->leave_title;
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
<a style="border-radius:15px; background:brown;color:#fff;padding:6px; box-shadow:1px 1px 1px #222; position:fixed; border:1px solid #fff; "href="javascript:void(0)" onclick="showPage('leave_report.php')"><i class="fas fa-close"></i> Close</a>
    <div id="patient_details">
        <h3 style="background:var(--otherColor); color:#fff"><?php echo $title?> Details for "<?php echo $row->last_name." ".$row->other_names?>"</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="nomenclature">
            <!-- <div class="profile_foto">
                <img src="<?php echo '../photos/'.$row->photo?>" alt="Photo">
            </div> -->
            <div class="inputs">
              
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
                <div class="data">
                    <label for="customer_store" style="color:var(--tertiaryColor)">Leave Status:</label>
                    <?php if($status == 0){?>
                        <input type="text" value="<?php echo "Pending"?>" style="color:var(--primaryColor)">
                    <?php }elseif($status == 1 && date("Y-m-d", strtotime($start)) > date("Y-m-d")){?>
                        <input type="text" value="<?php echo "Approved"?>" style="color:var(--tertiaryColor)">
                    <?php }elseif($status == 1 && date("Y-m-d", strtotime($start)) <= date("Y-m-d")){?>
                        <input type="text" value="<?php echo "Leave started"?>" style="color:green">
                    <?php }elseif($status == 1 && date("Y-m-d", strtotime($end)) < date("Y-m-d")){?>
                        <input type="text" value="<?php echo "Return Overdue"?>" style="color:var(--secondaryColor)">
                    <?php }elseif($status == 2){?>
                        <input type="text" value="<?php echo "Completed"?>" style="color:var(--otherColor)">
                    <?php }else{?>
                        <input type="text" value="<?php echo "Declined"?>" style="color:brown">
                    <?php }?>
                </div>
            </div>
        </section>    
        <section id="main_consult">
            <h3 style="background:var(--labColor); text-align:left">Leave Application Details</h3>
            
            <form style="padding:0!important; margin:0!important">
                <div class="inputs" style="margin:0!important; padding:10px 0!important">
                    
                    <div class="data" style="width:30%!important">
                        <label for="notes">Maximum Days Allowed</label>
                        <input type="text" readonly value="<?php echo $max_days?> days">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Start Date</label>
                        <input type="text" readonly value="<?php echo date("jS M, Y", strtotime($start))?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Selected End Date</label>
                        <input type="text" readonly value="<?php echo date("jS M, Y", strtotime($end))?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Expected Return Date</label>
                        <input type="text" readonly value="<?php echo date("jS M, Y", strtotime($end . "+ 1 day"))?>">
                    </div>
                    <?php if($status == 1){?>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Approved By:</label>
                        <input type="text" readonly value="<?php echo $approval?>">
                    </div>
                    <?php }?>
                    <?php if($status == -1){?>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Declined By:</label>
                        <input type="text" readonly value="<?php echo $approval?>">
                    </div>
                    <?php }?>
                    <?php if($status == -1 || $status == 1){?>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Date Done:</label>
                        <input type="text" readonly value="<?php echo date("d-M-Y, h:ia", strtotime($approved))?>">
                    </div>
                    <?php }?>
                    <?php if($status == 2){?>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Date Ended/Returned:</label>
                        <input type="text" readonly value="<?php echo date("d-M-Y, h:ia", strtotime($returned))?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Ended By:</label>
                        <input type="text" readonly value="<?php echo $ender?>">
                    </div>
                    <div class="data" style="width:30%!important">
                        <label for="notes">Total Days:</label>
                        <?php
                            $started = new DateTime($start);
                            $ended = new DateTime($returned);
                            $interval = $returned->diff($started);
                            $days_used = $interval->days;
                            echo $days_used . " day" . ($days_used != 1 ? "s" : "");
                            ?>
                        <input type="text" readonly value="<?php echo $days_used . " day" . ($days_used != 1 ? "s" : "")?>">
                    </div>
                    <?php }?>
                </div>
                <div class="inputs" style="padding:10px 0!important">
                    <div class="data" style="width:50%!important">
                        <label for="notes">Reason for Leave</label>
                        <textarea name="note" id="note" readonly style="min-height:100px"><?php echo $reason?></textarea>
                    </div>
                    
                    
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
