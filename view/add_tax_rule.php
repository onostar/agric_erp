<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    if(isset($_SESSION['user_id'])){
    $store = $_SESSION['store_id'];
?>

<div id="tax_rules" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('tax_rules.php')" title="Return to tax rules">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:60%; margin:20px"></div>
    <div class="add_user_form" style="width:30%; margin:20px">
        <h3 style="background:var(--moreColor)">Add Tax Rule</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <div class="data" style="width:100%">
                    <label for="title">Title</label>
                    <input type="text" id="title" name="title" required>
                </div>
                <div class="data" style="width:100%">
                    <label for="min_income">Minimum Income (NGN)</label>
                    <input type="number" id="min_income" name="min_income" required>
                </div>
                <div class="data" style="width:100%">
                    <label for="max_income">Maximum Income (NGN)</label>
                    <input type="number" id="max_income" name="max_income" required>
                </div>
                <div class="data" style="width:100%">
                    <label for="max_income">Tax Rate (%)</label>
                    <input type="number" id="tax_rate" name="tax_rate" required>
                </div>
                
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="addTaxRule()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
<?php
    }else{
        echo "Your session has expired! Kindly login again to continue";
    }