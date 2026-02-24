<?php
date_default_timezone_set("Africa/Lagos");
session_start();
$date = date("Y-m-d H:i:s");
if(isset($_SESSION['user_id'])){
    if(isset($_GET['staff'])){
        $staff = htmlspecialchars(stripslashes($_GET['staff']));
        $beneficiary_id = htmlspecialchars(stripslashes($_GET['beneficiary']));
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        //fetch beneficiary details
        $fetch = new selects();
        $details = $fetch->fetch_details_cond('beneficiaries', 'beneficiary_id', $beneficiary_id);
        foreach($details as $detail){
            $beneficiary = $detail->beneficiary;
            $phone = $detail->phone;
            $address = $detail->address;
            $entitlement = $detail->entitlement;
            $relation = $detail->relation;
            $gender = $detail->gender;
        }

?>
<div class="add_user_form" style="width:80%">
        <h3 style="background:var(--tertiaryColor);text-transform:uppercase">Edit Beneficiary Details</h3>

        <!-- <form method="POST" id="addUserForm"> -->
        <form>
            <div class="inputs" style="gap:.9rem; justify-content:left">
                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff?>">
                <input type="hidden" name="beneficiary_id" id="beneficiary_id" value="<?php echo $beneficiary_id?>">
                <div class="data" style="width:23%">
                    <label for="beneficiary">Beneficiary Full Name</label>
                    <input type="text" name="beneficiary" id="beneficiary" value="<?php echo $beneficiary?>" placeholder="Enter beneficiary full name">
                </div>
                <div class="data" style="width:23%">
                    <label for="customer">Gender <span class="important">*</span></label>
                    <select name="ben_gender" id="ben_gender">
                        <option value="<?php echo $gender?>" selected><?php echo $gender?></option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="relationship">Relationship <span class="important">*</span></label>
                    <input type="text" name="ben_relationship" id="ben_relationship" value="<?php echo $relation?>" placeholder="Enter relationship">
                </div>
                <div class="data" style="width:23%">
                    <label for="phone_number">Phone Number <span class="important">*</span></label>
                    <input type="text" name="ben_phone_number" id="ben_phone_number" value="<?php echo $phone?>">
                </div>
                <div class="data" style="width:23%">
                    <label for="address">Residential Address <span class="important">*</span></label>
                    <input type="text" name="ben_address" id="ben_address" value="<?php echo $address?>" placeholder="Enter address">
                </div>
                <div class="data" style="width:23%">
                    <label for="entitlement">Entitlement (%) <span class="important">*</span></label>
                    <input type="number" name="entitlement" id="entitlement" value="<?php echo $entitlement?>" placeholder="Enter entitlement percentage">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" onclick="updateBeneficiary()">Update <i class="fas fa-save"></i></button>
                    <button type="button" onclick="closeBen()" style="background:brown">Close <i class="fas fa-close"></i></button>
                </div>
            </div>
        </form>    
    </div>
<?php
    }
       }else{
           echo "<div class='error'><p>Please login to add beneficiary! <i class='fas fa-thumbs-down'></i></p></div>";
           echo "<script>alert('Your session has expired. Please login to add beneficiary!');</script>";
       }
       ?>