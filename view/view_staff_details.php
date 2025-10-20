<div id="edit_customer">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['customer'])){
            $customer = $_GET['customer'];
            //get customer name
            $get_customer = new selects();
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
</style>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('staff_list.php')"><i class="fas fa-close"></i> Close</a>
    <div id="patient_details">
        <h3 style="background:var(--tertiaryColor); color:#fff"><?php echo ucwords($row->title)." ".strtoupper($row->last_name." ".$row->other_names)?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="nomenclature">
            <!-- <div class="profile_foto">
                <img src="<?php echo '../photos/'.$row->photo?>" alt="Photo">
            </div> -->
            <div class="inputs">
                <div class="data">
                    <label for="customer">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo $row->last_name?>" readonly>
                </div>
                <div class="data">
                    <label for="other_names">Other Names:</label>
                    <input type="text" name="other_names" id="other_names" value="<?php echo $row->other_names?>" readonly>
                   
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
                    <label for="category">Group:</label>
                    <input type="text" value="<?php echo $row->staff_group?>" readonly>
                </div>
                <div class="data">
                    <label for="category">Marital Status:</label>
                    <input type="text" value="<?php echo $row->marital_status?>" readonly>
                </div>
                <div class="data">
                    <label for="category">Religion:</label>
                    <input type="text" value="<?php echo $row->religion?>" readonly>
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
                    <label for="ailment">Discipline:</label>
                        <?php
                              $get_reg = new selects();
                              $details = $get_reg->fetch_details_cond('disciplines', 'discipline_id', $row->discipline);
                              if(gettype($details) == 'array'){
                                  foreach($details as $detail){
                                      $discipline_name = $detail->discipline;
                                  }
                              }
                              if(gettype($details) == "string"){
                                $discipline_name = "";
                              }
                              
                        ?>
                        <input type="text" value="<?php echo $discipline_name?>" readonly>
                </div>
                <div class="data">
                        <label for="customer">Next Of Kin:</label>
                        <input type="text" name="last_name" id="last_name" value="<?php echo $row->nok?>" readonly>
                    </div>
                    <div class="data">
                        <label for="other_names">Relationship:</label>
                        <input type="text"  value="<?php echo $row->nok_relation?>" readonly>
                    
                    </div>
                    <div class="data">
                        <label for="other_names">NOK Phone Number:</label>
                        <input type="text"  value="<?php echo $row->nok_phone?>" readonly>
                    
                    </div>
                
            </div>
        </section>    
        <section id="main_consult">
            <h3 style="background:var(--otherColor); text-align:left">Account Information</h3>
            <div class="nomenclature" style="box-shadow:none">
                <div class="inputs" style="width:100%; align-items:flex-end">
                        <div class="data" style="width:auto!important">
                            <label style="background:transparent; color:green; text-align:left;width:auto" for="other_names">Bank:</label>
                            <?php
                                $get_reg = new selects();
                                $details = $get_reg->fetch_details_cond('banks', 'bank_id', $row->bank);
                                if(gettype($details) == 'array'){
                                    foreach($details as $detail){
                                        $bank_name = $detail->bank;
                                    }
                                }
                                if(gettype($details) == "string"){
                                    $bank_name = "";
                                }
                            ?>
                            <input type="text" value="<?php echo $bank_name?>" readonly>
                    </div>
                    <div class="data" style="width:auto!important">
                        <label style="background:transparent; color:green; text-align:left;width:auto" for="other_names">Account Number:</label>
                        <input type="text"  value="<?php echo $row->account_num?>" readonly>
                    
                    </div>
                    <div class="data" style="width:auto!important">
                        <label style="background:transparent; color:green; text-align:left;width:auto" for="other_names">Pension Manager:</label>
                        <input type="text"  value="<?php echo $row->pension?>" readonly>
                    
                    </div>
                    <div class="data" style="width:auto!important">
                        <label style="background:transparent; color:green; text-align:left;width:auto" for="other_names">Pension Number:</label>
                        <input type="text"  value="<?php echo $row->pension_num?>" readonly>
                    
                    </div>
                    <div class="data" style="border:none">
                        <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('staff_list.php')"><i class="fas fa-close"></i> Close</a>
                    </div>
                </div>
                
            </div>
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
