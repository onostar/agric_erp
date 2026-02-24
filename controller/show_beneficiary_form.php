<?php
date_default_timezone_set("Africa/Lagos");
session_start();
$date = date("Y-m-d H:i:s");
if(isset($_SESSION['user_id'])){
    if(isset($_GET['staff'])){
        $staff = htmlspecialchars(stripslashes($_GET['staff']));

?>
<div class="add_user_form" style="width:80%">
        <h3 style="background:var(--tertiaryColor);text-transform:uppercase">Add Beneficiary</h3>

        <!-- <form method="POST" id="addUserForm"> -->
        <form>
            <div class="inputs" style="gap:.9rem; justify-content:left">
                <input type="hidden" name="staff_id" id="staff_id" value="<?php echo $staff?>">
                <div class="data" style="width:23%">
                    <label for="beneficiary">Beneficiary Full Name</label>
                    <input type="text" name="beneficiary" id="beneficiary" placeholder="Enter beneficiary full name">
                </div>
                <div class="data" style="width:23%">
                    <label for="customer">Gender <span class="important">*</span></label>
                    <select name="ben_gender" id="ben_gender">
                        <option value="" selected disabled>Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="relationship">Relationship <span class="important">*</span></label>
                    <input type="text" name="ben_relationship" id="ben_relationship" placeholder="Enter relationship">
                </div>
                <div class="data" style="width:23%">
                    <label for="phone_number">Phone Number <span class="important">*</span></label>
                    <input type="text" name="ben_phone_number" id="ben_phone_number" placeholder="Enter phone number">
                </div>
                <div class="data" style="width:23%">
                    <label for="address">Residential Address <span class="important">*</span></label>
                    <input type="text" name="ben_address" id="ben_address" placeholder="Enter address">
                </div>
                <div class="data" style="width:23%">
                    <label for="entitlement">Entitlement (%) <span class="important">*</span></label>
                    <input type="number" name="entitlement" id="entitlement" placeholder="Enter entitlement percentage">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" onclick="addMoreBeneficiary()">Add Beneficiary <i class="fas fa-plus"></i></button>
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