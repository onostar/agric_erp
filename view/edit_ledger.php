<div id="update_customer">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['ledger'])){
            $ledger = $_GET['ledger'];
            //get customer name
            $get_ledger = new selects();
            $rows = $get_ledger->fetch_details_cond('ledgers', 'ledger_id', $ledger);
            foreach($rows as $row){

?>
    <button class="page_navs" id="back" style="margin:0 50px"onclick="showPage('update_ledger.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="add_user_form" style="width:80%; margin:0 50px!important">
        <h3>Edit <?php echo $row->ledger?> details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:.5rem;">
                <div class="data" style="width:23%">
                    <label for="account_group">Account Group</label>
                    <select name="group" id="group" onchange="getSubGroup(this.value)">
                        <option value="<?php echo $row->account_group?>">
                            <?php
                                //get group
                                $get_group = new selects();
                                $grs = $get_group->fetch_details_group('account_groups', 'account_group', 'account_id', $row->account_group);
                                echo $grs->account_group;
                            ?>
                        </option>
                        <?php
                            $get_dep = new selects();
                            $rowsss = $get_dep->fetch_details('account_groups');
                            foreach($rowsss as $rowss){
                        ?>
                        <option value="<?php echo $rowss->account_id?>"><?php echo $rowss->account_group?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="sub_group">Account Sub-group</label>
                    <select name="sub_group" id="sub_group" onchange="getClass(this.value)">
                        <option value="<?php echo $row->sub_group?>">
                            <?php
                                //get group
                                $get_group = new selects();
                                $grs = $get_group->fetch_details_group('account_sub_groups', 'sub_group', 'sub_group_id', $row->sub_group);
                                echo $grs->sub_group;
                            ?>
                        </option>
                        
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="account_class">Account Class</label>
                    <select name="account_class" id="account_class">
                        <option value="<?php echo $row->class?>">
                            <?php
                                //get group
                                $get_group = new selects();
                                $grs = $get_group->fetch_details_group('account_class', 'class', 'class_id', $row->class);
                                echo $grs->class;
                            ?>
                        </option>
                        
                    </select>
                </div>
                <div class="data" style="width:23%">
                    <label for="customer">Ledger Name</label>
                    <input type="text" name="ledger_name" id="ledger_name" value="<?php echo $row->ledger?>" required>
                    <input type="hidden" name="ledger_id" id="ledger_id" value="<?php echo $row->ledger_id?>" required>
                </div>
                
            </div>
            <div class="inputs">
                <button type="submit" id="update_customer" name="update_customer" onclick="updateLedger()">Update details <i class="fas fa-save"></i></button>
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
