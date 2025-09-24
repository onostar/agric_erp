<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="add_category" class="displays">
    <div class="info" style="width:40%;margin:0"></div>
    <div class="add_user_form" style="width:30%; margin:0">
        <h3>Add Account Class</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:1rem">
                <div class="data" style="width:100%">
                    <label for="group">Account group</label>
                    <select name="group" id="group" onchange="getSubGroup(this.value)">
                        <option value=""selected required>Select group</option>
                        <?php
                            $get_dep = new selects();
                            $rows = $get_dep->fetch_details('account_groups');
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->account_id?>"><?php echo $row->account_group?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:100%">
                    <label for="sub_group">Account Sub-group</label>
                    <select name="sub_group" id="sub_group">
                        <option value="">Select sub-group</option>
                    </select>
                </div>
                <div class="data" style="width:100%">
                    <label for="class">Account Class</label>
                    <input type="text" name="account_class" id="account_class">
                </div>
                
            </div>
            <div class="inputs">
                <button type="submit" id="add_cat" name="add_cat" onclick="addClass()">Save record <i class="fas fa-layer-group"></i></button>
            </div>
                            </section>    
    </div>
</div>
