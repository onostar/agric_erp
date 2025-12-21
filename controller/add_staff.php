<?php
    date_default_timezone_set("Africa/Lagos");

    session_start();
    $user = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];
    $last_name = strtoupper(htmlspecialchars(stripslashes($_POST['last_name'])));
    $other_names = strtoupper(htmlspecialchars(stripslashes($_POST['other_names'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes($_POST['address'])));
    $email = htmlspecialchars(stripslashes($_POST['email']));
    // $store = htmlspecialchars(stripslashes(($_POST['customer_store'])));
    $dob = htmlspecialchars(stripslashes($_POST['dob']));
    $staff_id = htmlspecialchars(stripslashes(($_POST['staff_id'])));
    $title = htmlspecialchars(stripslashes($_POST['title']));
    $gender = htmlspecialchars(stripslashes($_POST['gender']));
    $marital_status = htmlspecialchars(stripslashes($_POST['marital_status']));
    $religion = htmlspecialchars(stripslashes(($_POST['religion'])));
    $department = htmlspecialchars(stripslashes($_POST['department']));
    $nok = strtoupper(htmlspecialchars(stripslashes($_POST['nok'])));
    $discipline = ucwords(htmlspecialchars(stripslashes($_POST['discipline'])));
    $nok_phone = htmlspecialchars(stripslashes($_POST['nok_phone']));
    $relation = strtoupper(htmlspecialchars(stripslashes($_POST['nok_relation'])));
    $category = htmlspecialchars(stripslashes($_POST['staff_category']));
    $group = htmlspecialchars(stripslashes($_POST['staff_group']));
    $designation = htmlspecialchars(stripslashes($_POST['designation']));
    $bank = htmlspecialchars(stripslashes($_POST['bank']));
    $account = htmlspecialchars(stripslashes($_POST['account_num']));
    $pension = htmlspecialchars(stripslashes($_POST['pension']));
    $pension_num = htmlspecialchars(stripslashes($_POST['pension_num']));
    $employed = htmlspecialchars(stripslashes($_POST['employed']));
    $spouse = strtoupper(htmlspecialchars(stripslashes($_POST['spouse_name'])));
    $spouse_phone = htmlspecialchars(stripslashes($_POST['spouse_phone']));
    $date = date("Y-m-d H:i:s");
    $todays_date = date("dmyh");
    /* if($service != ""){
        $service = $service;
    }else{
        $service = "Registration";
    } */
    $data = array(
        'last_name' => $last_name,
        'other_names' => $other_names,
        'phone' => $phone,
        'email_address' => $email,
        'home_address' => $address,
        'gender' => $gender,
        'dob' => $dob,
        'employed' => $employed,
        'staff_number' => $staff_id,
        'title' => $title,
        'discipline' => $discipline,
        'religion' => $religion,
        'marital_status' => $marital_status,
        'spouse' => $spouse,
        'spouse_phone' => $spouse_phone,
        'nok' => $nok,
        'staff_group' => $group,
        'nok_phone' => $nok_phone,
        'nok_relation' => $relation,
        'staff_category' => $category,
        'designation' => $designation,
        'department' => $department,
        'bank' => $bank,
        'account_num' => $account,
        'pension_num' => $pension_num,
        'pension' => $pension,
        'photo' => 'user.png',
        'reg_date' => $date,
        'posted_by' => $user,
        'store' => $store
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";

   //check if staff exists
   $check = new selects();
   $results = $check->fetch_count_2cond('staffs', 'last_name', $last_name, 'other_names', $other_names);
   $results2 = $check->fetch_count_cond('staffs', 'staff_number',  $staff_id);

   if($results > 0 || $results2 > 0){
       echo "<p class='exist' style='background:red;color#fff;'><span>$last_name $other_names</span> already exists!</p>";
   }else{
       //create staff record
       $add_data = new add_data('staffs', $data);
       $add_data->create_data();
       if($add_data){
        //get last inserted id and add into users table
        $ids = $check->fetch_lastInserted('staffs', 'staff_id');
        $staff_id = $ids->staff_id;
        
        $user_data = array(
            'full_name' => $last_name." ".$other_names,
            'username' => $phone,
            'user_password' => 123,
            'user_role' => 'Staff',
            'staff_id' => $staff_id,
            'store' => $store,
            'posted_by' => $user,
            'reg_date' => $date
        );
        $add_user = new add_data('users', $user_data);
        $add_user->create_data();
        //update staff record with user id
        $user_ids = $check->fetch_lastInserted('users', 'user_id');
        $user_id = $user_ids->user_id;
        
        $update_user = new Update_table();
        $update_user->update('staffs', 'user_id', 'staff_id', $user_id, $staff_id);
        echo "<div class='success'><p><span>$last_name $other_names</span> added as a staff  successfully!</p></div>";
                
        //display beneficiaries form
        ?>
        <div class="info"></div>
    <div class="add_user_form" style="width:80%">
        <h3 style="background:var(--tertiaryColor);text-transform:uppercase">Add Staff Beneficiary</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form>
            <div class="inputs" style="gap:.9rem; justify-content:left">
                <input type="hidden" name="staff" id="staff" value="<?php echo $staff_id?>">
                <div class="data" style="width:23%">
                    <label for="beneficiary">Beneficiary Full Name</label>
                    <input type="text" name="beneficiary" id="beneficiary" placeholder="Enter beneficiary full name">
                </div>
                <div class="data" style="width:23%">
                    <label for="customer">Gender <span class="important">*</span></label>
                    <select name="gender" id="gender">
                        <option value="" selected disabled>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="relationship">Relationship <span class="important">*</span></label>
                    <input type="text" name="relationship" id="relationship" placeholder="Enter relationship">
                </div>
                <div class="data" style="width:23%">
                    <label for="phone_number">Phone Number <span class="important">*</span></label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="Enter phone number">
                </div>
                <div class="data" style="width:23%">
                    <label for="address">Residential Address <span class="important">*</span></label>
                    <input type="text" name="address" id="address" placeholder="Enter address">
                </div>
                <div class="data" style="width:23%">
                    <label for="entitlement">Entitlement (%) <span class="important">*</span></label>
                    <input type="number" name="entitlement" id="entitlement" placeholder="Enter entitlement percentage">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" id="add_staff" name="add_staff" onclick="addBeneficiary()">Add Staff <i class="fas fa-plus"></i></button>
                </div>
            </div>
        </form>    
    </div>

<?php
       }
       
   }

   ?>