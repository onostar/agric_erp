<?php

    if (isset($_GET['item_id'])){
        $id = $_GET['item_id'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('staffs', 'staff_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="add_user_form priceForm" style="width:80%!important; margin:0!important;">
        <h3 style="background:var(--tertiaryColor)">Mark Attendance for  <?php echo strtoupper($row->last_name." ".$row->other_names)?></h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:.2rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="staff" id="staff" value="<?php echo $id?>" required>
                <div class="data" style="width:25%">
                    <label for="attendance_time">Resumption Time</label>
                    <input type="time" name="attendance_time" id="attendance_time" required>
                </div>
                <div class="data" style="width:40%">
                    <label for="remark">Remarks</label>
                    <input type="text" name="remark" id="remark">
                </div>
                
                <div class="data" style="width:auto">
                    <button type="button" onclick="markPresent()">Save <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return <i class='fas fa-angle-double-left'></i></a>
                </div>
                
            </div>
        </form>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>