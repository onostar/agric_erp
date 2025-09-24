<div id="update_customer" class="displays">

<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        $user_id = $_SESSION['user_id'];
?>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--tertiaryColor); color:#fff; text-align:left!important;">Edit Ledger Name</h3>

        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <div class="data" id="bar_items" style="width:100%; margin:0">
                    <label for="item"> Select Ledger</label>
                    
                    <input type="hidden" name="ledger" id="ledger">
                    <input type="text" name="item" id="item" required placeholder="Input ledger name or account number" onkeyup="getLedger(this.value)">
                    <div id="sales_item">
                        
                    </div>
                </div>
                
            </div>

        </section>
    </div>
    <div class="info" style="width:100%; margin:0"></div>
    <div class="stocked_in" style="width:100%"></div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>
</div>
