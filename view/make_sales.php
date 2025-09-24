<?php
    session_start();
    /* $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php"; */
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;

?>
<div id="make_sales">
<div id="sales_form" class="displays all_details">  
    <section class="addUserForm add_bill" style="padding:20px 0">
        <a href="javascript:void" style="background:brown" title="Make wholesale" onclick="showPage('wholesale.php')"><i class="fas fa-user-friends"></i> Customers</a>
        <a href="javascript:void" style="background:var(--otherColor)" title="add rep sales" onclick="showPage('rep_sales.php')"><i class="fas fa-house-user"></i> Distributors</a>
    </section>

</div>
<?php }?>