<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="stockin">
    <div class="displays">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--moreColor); text-align:left!important;" >Stockin Purchases by invoice</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                
                <div class="data">
                    <label for="Invoice">Invoice Number</label>
                    <input type="text" name="invoice" id="invoice" required>
                </div>
                <div class="data">
                    <label for="vendor">Supplier</label>
                    
                    <input type="text" name="supplier" id="supplier" required placeholder="Input vendor name" onkeyup="getSupplier(this.value)">
                    <input type="hidden" name="vendor" id="vendor">
                        <div id="transfer_item">
                            
                        </div>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemStockin(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    
                </div>
            </div>
        </section>
    </div>
    <div class="info" style="width:100%; margin:0"></div>
    <div class="stocked_in"></div>
</div>
</div>
<?php
    }else{
        echo "Your Session has expired. Please Login again to continue";
        exit();
        // header("Location: ../index.php");
    }
?>