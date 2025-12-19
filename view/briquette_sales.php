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
        <a href="javascript:void" style="background:var(--tertiaryColor)" title="Industrial Customers" onclick="showPage('wholesale.php')"><i class="fas fa-industry"></i> Industrial Use</a>
        <a href="javascript:void" style="background:var(--primaryColor)" title="add rep sales" onclick="showPage('rep_sales.php')"><i class="fas fa-house-user"></i> Non-Industrial Use</a>
    </section>

</div>
<?php }?>