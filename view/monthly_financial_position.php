<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="customer_statement" class="displays" style="width:90%!important">
    <div class="add_user_form" style="width:70%; margin:10px 0;">
        <h3 style="background:var(--moreColor); text-align:left!important;" >Monthly Financial Position</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="align-items:flex-end">
                
                <div class="data" style="width:30%;">
                    <label for="fin_month">Financial Month</label>
                    <select name="fin_month" id="fin_month">
                        <option value="">Select Month</option>
                        <option value="01">January</option>
                        <option value="02">February</option>
                        <option value="03">March</option>
                        <option value="04">April</option>
                        <option value="05">May</option>
                        <option value="06">June</option>
                        <option value="07">July</option>
                        <option value="08">August</option>
                        <option value="09">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                    </select>
                </div>
                <div class="data" style="width:30%;">
                    <label for="fin_year">Financial Year</label>
                    <select name="fin_year" id="fin_year">
                        <option value="">Select year</option>
                        <option value="2021">2021</option>
                        <option value="2022">2022</option>
                        <option value="2023">2023</option>
                        <option value="2024">2024</option>
                        <option value="2025">2025</option>
                        <option value="2026">2026</option>
                        <option value="2027">2027</option>
                        <option value="2028">2028</option>
                        <option value="2029">2029</option>
                        <option value="2030">2030</option>
                    </select>
                </div>
                <div class="data" style="width:30%;">
                    <button title="fetch financial position for the month" onclick="getMonthlyFinPos()">Get Financial Position</button>
                </div>
            </div>
        </section>
    </div>
    <div class="allResults new_data" style="width:100%; margin:0"></div>
    
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>