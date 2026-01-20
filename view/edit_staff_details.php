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
    <div class="add_user_form" style="width:90%">
        <h3>Edit <?php echo $row->last_name." ".$row->other_names?> details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:.5rem;">
                <div class="data" style="width:24%">
                    <label for="title">Title</label>
                    <select name="title" id="title">
                        <option value="<?php echo $row->title?>" selected><?php echo $row->title?></option>
                        <option value="Mr.">Mr.</option>
                        <option value="Mrs">Mrs</option>
                        <option value="Master">Master</option>
                        <option value="Miss">Miss</option>
                        <option value="Dr.">Dr.</option>
                        <option value="Chief">Chief</option>
                        <option value="Prof">Prof</option>
                        <option value="Hon">Hon</option>
                        <option value="Pastor">Pastor</option>
                        <option value="Eng.">Eng.</option>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="gender">Gender</label>
                    <select name="gender" id="gender">
                        <option value="<?php echo $row->gender?>" selected><?php echo $row->gender?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="customer">Last Name <span class="important">*</span></label>
                    <input type="text" name="last_name" id="last_name" value="<?php echo $row->last_name?>" required>
                    <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $row->staff_id?>" required>
                </div>
                <div class="data" style="width:24%">
                    <label for="other_names">Other Names <span class="important">*</span></label>
                    <input type="text" name="other_names" id="other_names" value="<?php echo $row->other_names?>" required>
                   
                </div>
                <div class="data" style="width:24%">
                    <label for="phone_number">Phone number <span class="important">*</span></label>
                    <input type="text" name="phone_number" id="phone_number" required value="<?php echo $row->phone?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="Address">Address <span class="important">*</span></label>
                    <input type="text" name="address" id="address" required value="<?php echo $row->home_address?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="email">Email address <span class="important">*</span></label>
                    <input type="text" name="email" id="email" placeholder="example@mail.com" required value="<?php echo $row->email_address?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="customer_store">Date of birth <span class="important">*</span></label>
                    <input type="date" name="dob" id="dob" value="<?php echo date("Y-m-d", strtotime($row->dob))?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="marital_status">Marital Status <span class="important">*</span></label>
                    <select name="marital_status" id="marital_status">
                        <option value="<?php echo $row->marital_status?>" selected><?php echo $row->marital_status?></option>
                        <option value="Single">Single</option>
                        <option value="Married">Married</option>
                        <option value="Divorced">Divorced</option>
                        <option value="Widowed">Widowed</option>
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="spouse_name">Spouse Name </label>
                    <input type="text" name="spouse_name" id="spouse_name" value="<?php echo $row->spouse?>">

                </div>
                <div class="data" style="width:23%" class="spouse_info">
                    <label for="spouse_phone">Spouse Phone Number </label>
                    <input type="tel" name="spouse_phone" id="spouse_phone" value="<?php echo $row->spouse_phone?>">

                </div>
                <div class="data" style="width:24%">
                    <label for="religion">Religion <span class="important">*</span></label>
                    <select name="religion" id="religion">
                        <option value="<?php echo $row->religion?>" selected><?php echo $row->religion?></option>
                        <option value="Christian">Christian</option>
                        <option value="Muslim">Muslim</option>
                        <option value="Others">Others</option>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="staffid">Staff ID </label>
                    <input type="text" name="staff_num" id="staff_num" required value="<?php echo $row->staff_number?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="employed">Employment Date <span class="important">*</span></label>
                    <input type="date" name="employed" id="employed" value="<?php echo date("Y-m-d", strtotime($row->employed))?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="staff_group">Staff Group <span class="important">*</span></label>
                    <select name="staff_group" id="staff_group">
                        <option value="<?php echo $row->staff_group?>" selected><?php echo $row->staff_group?></option>
                        <option value="core staff">CORE STAFF</option>
                        <option value="contract staff">CONTRACT STAFF</option>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="staff_category">Staff Category <span class="important">*</span></label>
                    <select name="staff_category" id="staff_category">
                        <option value="<?php echo $row->staff_category?>" selected><?php echo $row->staff_category?></option>
                        <option value="junior staff">JUNIOR STAFF</option>
                        <option value="senior staff">SENIOR STAFF</option>
                        <option value="managment staff">MANAGEMENT STAFF</option>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="department">Department <span class="important">*</span></label>
                    <select name="department" id="department">
                        <?php
                            $get_dep = new selects();
                            $deps = $get_dep->fetch_details_cond('staff_departments',  'department_id', $row->department);
                            if(gettype($deps) == 'array'){
                                foreach($deps as $dep){
                                    $department = $dep->department;
                                }
                            }
                            if(gettype($deps) == 'string'){
                                $department = "";
                            }
                        ?>
                        <option value="<?php echo $row->department?>" selected><?php echo $department?></option>
                        <?php
                            $get_dep = new selects();
                            $details = $get_dep->fetch_details_order('staff_departments', 'department');
                            if(gettype($details) == 'array'){
                                foreach($details as $detail){
                                
                        ?>
                        <option value="<?php echo $detail->department_id?>"> <?php echo strtoupper($detail->department)?></option>
                        <?php }}?>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="discipline">Discipline <span class="important">*</span></label>
                    <select name="discipline" id="discipline">
                        <?php
                            $get_dep = new selects();
                            $dis = $get_dep->fetch_details_cond('disciplines',  'discipline_id', $row->discipline);
                            if(gettype($dis) == 'array'){
                                foreach($dis as $di){
                                    $disciplines = $di->discipline;
                                }
                            }
                            if(gettype($dis) == 'string'){
                                $disciplines = "";
                            }
                        ?>
                        <option value="<?php echo $row->discipline?>" selected><?php echo $disciplines?></option>
                        <?php
                            $get_dep = new selects();
                            $details = $get_dep->fetch_details_order('disciplines', 'discipline');
                            if(gettype($details) == 'array'){
                            foreach($details as $detail){
                                
                        ?>
                        <option value="<?php echo $detail->discipline_id?>"> <?php echo strtoupper($detail->discipline)?></option>
                        <?php }}?>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="designation">Designation <span class="important">*</span></label>
                    <select name="designation" id="designation">
                        <?php
                            $get_dep = new selects();
                            $deps = $get_dep->fetch_details_cond('designations',  'designation_id', $row->designation);
                            if(gettype($deps) == 'array'){
                                foreach($deps as $dep){
                                    $designation = $dep->designation;
                                }
                            }
                            if(gettype($deps) == 'string'){
                                $designation = "";
                            }
                        ?>
                        <option value="<?php echo $row->designation?>" selected><?php echo $designation?></option>
                        <?php
                            $get_dep = new selects();
                            $details = $get_dep->fetch_details_order('designations', 'designation');
                            if(gettype($details) == 'array'){
                                foreach($details as $detail){
                                
                        ?>
                        <option value="<?php echo $detail->designation_id?>"> <?php echo strtoupper($detail->designation)?></option>
                        <?php }}?>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="nok">Next of Kin <span class="important">*</span></label>
                    <input type="text" name="nok" id="nok" required value="<?php echo $row->nok?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="nok">Next of Kin Relationship <span class="important">*</span></label>
                    <input type="text" name="nok_relation" id="nok_relation" required value="<?php echo $row->nok_relation?>">
                </div>
                
                <div class="data" style="width:24%">
                    <label for="nok">Next of Kin Phone Number <span class="important">*</span></label>
                    <input type="tel" name="nok_phone" id="nok_phone" required value="<?php echo $row->nok_phone?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="bank">Bank <span class="important">*</span></label>
                    <select name="bank" id="bank">
                        <?php
                           echo $row->bank;
                        ?>
                        <option value="<?php echo $row->bank?>" selected><?php echo $row->bank?></option>
                       <option value="" selected disabled>Select Bank</option>
                        <option>Access Bank</option>
                        <option>Citibank Nigeria</option>
                        <option>Ecobank Nigeria</option>
                        <option>Fidelity Bank</option>
                        <option>First Bank of Nigeria</option>
                        <option>First City Monument Bank (FCMB)</option>
                        <option>Globus Bank</option>
                        <option>Guaranty Trust Bank (GTBank)</option>
                        <option>Heritage Bank</option>
                        <option>Jaiz Bank</option>
                        <option>Keystone Bank</option>
                        <option>Parallex Bank</option>
                        <option>Polaris Bank</option>
                        <option>PremiumTrust Bank</option>
                        <option>Providus Bank</option>
                        <option>Stanbic IBTC Bank</option>
                        <option>Standard Chartered Bank Nigeria</option>
                        <option>Sterling Bank</option>
                        <option>SunTrust Bank</option>
                        <option>Titan Trust Bank</option>
                        <option>Union Bank of Nigeria</option>
                        <option>United Bank for Africa (UBA)</option>
                        <option>Unity Bank</option>
                        <option>Wema Bank</option>
                        <option>Zenith Bank</option>
                    </select>
                </div>
                <div class="data" style="width:24%">
                    <label for="nok">Account Number <span class="important">*</span></label>
                    <input type="text" name="account_num" id="account_num" required value="<?php echo $row->account_num?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="nok">Pension Manager <span class="important">*</span></label>
                    <input type="text" name="pension" id="pension" required value="<?php echo $row->pension?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="nok">Pension Number <span class="important">*</span></label>
                    <input type="text" name="pension_num" id="pension_num" required value="<?php echo $row->pension_num?>">
                </div>
                <div class="data" style="width:50%">
                    <button type="submit" id="update_customer" name="update_customer" onclick="updateStaff()">Update details <i class="fas fa-save"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('staff_list.php')"><i class="fas fa-angle-double-left"></i> Return</a>
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
