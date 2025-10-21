<div id="add_staff">
    <style>
        @media screen and (max-width: 800px){
            .data{
                width:100%!important;
                margin:0!important;
            }
             .data input{
                margin:0!important;
             }
        }
    </style>
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

    <div class="info"></div>
    <div class="add_user_form" style="width:60%; margin:0 50px!important">
        <h3 style="background:var(--tertiaryColor);text-transform:uppercase">Apply for staff Leave</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.9rem;">
                <div class="data" style="width:48%">
                    <label for="staff">Employee</label>
                    <input type="hidden" id="staff" name="staff">
                    <input type="search" id="employee" name="employee" placeholder="enter employee name or number" onkeyup="getEmployee(this.value, 'get_employee.php')">
                    <div id="sales_item">

                    </div>
                </div>
                <div class="data" style="width:48%">
                    <label for="leave">Leave Type</label>
                    <input type="hidden" id="leave" name="leave">
                    <input type="search" id="leave_type" name="leave_type" placeholder="search leave type" onkeyup="getLeave(this.value)">
                    <div id="transfer_item">

                    </div>
                </div>
                <div class="data" style="width:100%" id="leave_details">

                </div>
                <div class="data" style="width:31%">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date">
                </div>
                <div class="data" style="width:31%">
                    <label for="end_date">End Date</label>
                    <input type="date" id="end_date" name="end_date" oninput="checkMaxDays()">
                </div>
                <div class="data" style="width:31%">
                    <label for="total_days">Total Days</label>
                    <input type="number" id="total_days" name="total_days" readonly style="background:#dadada;">
                </div>
                <div class="data" style="width:100%">
                    <label for="reason">Reason for Leave</label>
                    <textarea name="reason" id="reason"></textarea>
                </div>
                <div class="data" style="width:auto">
                    <button type="submit" id="add_staff" name="add_staff" onclick="applyLeave()">Application <i class="fas fa-paper-plane"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php 
    }else{
        echo "Your session has expired! Please login again";
    }
?>