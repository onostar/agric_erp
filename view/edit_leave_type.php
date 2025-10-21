<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        if(isset($_GET['leave'])){
            $leave = $_GET['leave'];
            $get_details = new selects();
            $details = $get_details->fetch_details_cond('leave_types', 'leave_id', $leave);
            foreach($details as $detail){
                $title = $detail->leave_title;
                $max_days = $detail->max_days;
                $description = $detail->description;
                $status = $detail->leave_status;
            }
            
?>

<div id="crop_cycle" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('leave_types.php')" title="Return to leave types">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:60%; margin:20px"></div>
    <div class="add_user_form" style="width:40%; margin:20px">
        <h3 style="background:var(--primaryColor)">Update <?php echo $title?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="leave" id="leave" value="<?php echo $leave?>">
                <div class="data" style="width:100%;">
                   <label for="title"> Leave Title</label>
                   <input type="text" name="title" id="title" value="<?php echo $title?>"required>
                </div>
                <div class="data" style="width:100%">
                    <label for="max_days">Maximum Days</label>
                    <input type="number" name="max_days" id="max_days" value="<?php echo $max_days?>" required>
                </div>
                <div class="data" style="width:100%;">
                   <label for="description">Description</label>
                   <textarea name="description" id="description" rows="5"><?php echo $description?></textarea>
                </div>
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="editLeaveType()">Update record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php
    }else{
        echo "<p style='text-align:center; color:red; font-weight:bold'>No leave type selected for edit</p>";
    }
}else{
    echo "<p style='text-align:center; color:red; font-weight:bold'>Please login to continue</p>";
}