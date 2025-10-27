<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="addUser" class="displays">
    <div class="info"></div>
    <div class="add_user_form" style="width:90%">
        <h3>Add Users</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div>
                    <input type="text" name="item" id="item" required placeholder="Search item" onkeyup="getItemDetails(this.value, 'get_staff_name.php')">
                    <div id="sales_item" style="position:absolute">
                        
                    </div>
                    <input type="hidden" name="full_name" id="full_name">
                    <input type="hidden" name="staff_id" id="staff_id">
                </div>
                
                <input type="text" name="username" id="username" placeholder="Enter username" required>
                <select name="user_role" id="user_role" required style="padding:10px;border-radius:10px">
                    <option value="" selected>Select role</option>
                    <option value="Admin">Admin</option>
                    <option value="cashier">Cashier</option>
                    <option value="sales rep">Sales Rep</option>
                    <option value="Accountant">Accountant</option>
                    <option value="Inventory Officer">Inventory Officer</option>
                </select>
                <select name="store_id" id="store_id" style="padding:10px; border-radius:10px">
                    <option value=""selected required>select location</option>
                    <?php
                        $get_str = new selects();
                        $rows = $get_str->fetch_details_order('stores', 'store');
                        foreach($rows as $row){
                    ?>
                    <option value="<?php echo $row->store_id?>"><?php echo $row->store?></option>
                    <?php } ?>
                </select>
                <button type="button" id="add_user" name="add_user" title="add user" onclick="addUser()"><i class="fas fa-plus"></i></button>
            </div>
        </section>    
        <!-- </form> -->
    </div>
</div>
