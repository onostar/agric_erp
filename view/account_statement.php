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
        <h3 style="background:var(--otherColor); text-align:left!important;" >Check Account statement</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                
                <div class="data">
                    <label for="fromDate">Start Date</label>
                    <input type="date" name="fromDate" id="fromDate" required>
                </div>
                <div class="data">
                    <label for="toDate">End Date</label>
                    <input type="date" name="toDate" id="toDate" required>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <input type="text" name="customer" id="customer" required placeholder="Input ledger name" onkeyup="getAccount(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    
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