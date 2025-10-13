<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        //generate receipt invoice
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "PO".$store.$todays_date.$ran_num.$user_id;
        // $_SESSION['invoice'] = $invoice;
    
?>

<div id="stockin">
    <div class="displays">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--labColor); text-align:left!important;" >Raise Purchase Order</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="vendor">Supplier</label>
                    <input type="hidden" id="invoice" name="invoice" value="<?php echo $invoice?>">
                    <input type="text" name="supplier" id="supplier" required placeholder="Search vendor name" onkeyup="getPOSupplier(this.value)">
                    <input type="hidden" name="vendor" id="vendor">
                        <div id="transfer_item">
                            
                        </div>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemPO(this.value)">
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