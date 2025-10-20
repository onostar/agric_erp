<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    $farm = $_SESSION['store_id'];
?>

<div id="crop_cycle" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('leave_types.php')" title="Return fot farm fields">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:40%; margin:20px"></div>
    <div class="add_user_form" style="width:40%; margin:0px">
        <h3 style="background:var(--tertiaryColor)">Create a New Leave type</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <div class="data" style="width:100%">
                    <label for="title">Leave Title</label>
                    <input type="text" id="title" name="title" placeholder="Input leave title" required>
                </div>
                <div class="data" style="width:100%;">
                   <label for="max_days"> Maximum Days</label>
                   <input type="number" name="max_days" id="max_days" required>
                </div>
                <div class="data" style="width:100%;">
                   <label for="description">Description</label>
                   <textarea type="text" rows="5" name="description" id="description" placeholder="Enter Leave description"></textarea>
                </div>
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="addLeave()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
