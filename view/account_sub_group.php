<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>
<div id="add_category" class="displays">
    <div class="info" style="width:50%;margin:0"></div>
    <div class="add_user_form" style="width:50%;margin:0">
        <h3>Add account sub-group</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <form class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="group">Select Account group</label>
                    <select name="group" id="group">
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
                <div class="data">
                    <label for="category">Sub-group</label>
                    <input type="text" name="sub_group" id="sub_group" placeholder="" required>
                </div>
                
            </div>
            <div class="inputs">
                <button type="submit" id="add_cat" name="add_cat" onclick="addSubGroup()">Save record <i class="fas fa-layer-group"></i></button>
            </div>
        </form>    
    </div>
</div>
