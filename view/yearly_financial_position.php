<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="customer_statement" class="displays" style="width:90%!important">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--tertiaryColor); text-align:left!important;">Yearly Financial Position</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="align-items:flex-end">
                
                <div class="data" style="width:50%;">
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
                <div class="data" style="width:40%;">
                    <button title="fetch financial position for the month" onclick="getYearlyFinPos()">Get Financial Position</button>
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