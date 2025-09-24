<?php

    $group = htmlspecialchars(stripslashes($_POST['group']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_cat = new selects();
    $rows = $get_cat->fetch_details_cond('account_sub_groups', 'account_group', $group);
?>
<option value="">Select Sub-group</option>
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            

?>
    <option value="<?php echo $row->sub_group_id?>"><?php echo $row->sub_group?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>No account group available</option>";
    }
?>