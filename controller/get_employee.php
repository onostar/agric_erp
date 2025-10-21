<?php

    $employee_name = htmlspecialchars(stripslashes($_POST['employee_name']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_employee = new selects();
    $rows = $get_employee->fetch_details_like3Col1Con('staffs', 'last_name', 'other_names', 'staff_number', $employee_name, 'staff_status', 0);
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
            
?>
    <div class="results">
        <a href="javascript:void(0)" onclick="selectEmployee(<?php echo $row->staff_id?>, '<?php echo $row->last_name.' '.$row->other_names?>')">
            <?php echo $row->title.' '.$row->last_name.' '.$row->other_names?>
        </a>
    </div>
    
<?php
        }   
    }else{
        echo "<option value=''selected>No result found</option>";
    }
?>