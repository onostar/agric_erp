<?php

    $sub_group = htmlspecialchars(stripslashes($_POST['sub_group']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_cat = new selects();
    $rows = $get_cat->fetch_details_cond('account_class', 'sub_group', $sub_group);
?>
<option value="">Select Account Class</option>
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            

?>
    <option value="<?php echo $row->class_id?>"><?php echo $row->class?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>No account class</option>";
    }
?>