<?php
    if(isset($_GET['leave_id'])){
        $leave = $_GET['leave_id'];
        include "../classes/dbh.php";
        include "../classes/select.php";

        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('leave_types', 'leave_id', $leave);
        foreach($rows as $row){
            $title = $row->leave_title;
            $max_days = $row->max_days;
            $description = $row->description;
        }
    ?>
        <div class="data" style="width:27%">
            <label for="title">Leave title</label>
            <input type="text" value="<?php echo $title?>" readonly style="background:#cdcdcd">
        </div>
        <div class="data" style="width:18%">
            <label for="max_days">Max Days</label>
            <input type="number" value="<?php echo $max_days?>" readonly style="background:#cdcdcd">
        </div>
        <div class="data" style="width:50%">
            <label for="details">Description</label>
            <input type="text" value="<?php echo $description?>" readonly style="background:#cdcdcd">
        </div>
    <?php
    }