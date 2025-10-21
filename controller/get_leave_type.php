<?php

    $leave_name = htmlspecialchars(stripslashes($_POST['leave_name']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_employee = new selects();
    $rows = $get_employee->fetch_details_like1Cond('leave_types', 'leave_title', $leave_name, 'leave_status', 0);
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            
?>
    <div class="results">
        <a href="javascript:void(0)" onclick="selectLeave(<?php echo $row->leave_id?>, '<?php echo $row->leave_title?>')">
            <?php echo $row->leave_title?>
        </a>
    </div>
    
<?php
        }   
    }else{
        echo "<option value=''selected>No result found</option>";
    }
?>