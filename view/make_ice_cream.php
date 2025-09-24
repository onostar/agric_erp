<div id="produce" class="displays">
<!-- please note this iactually a reverse of the make product -->
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        $user_id = $_SESSION['user_id'];
?>
<?php
        //generate product number
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "PR".$store.$todays_date.$ran_num.$user_id;
        // $_SESSION['invoice'] = $invoice;
    ?>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--otherColor); color:#fff; text-align:left!important;">Make new products</h3>

        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <input type="hidden" name="product_num" id="product_num" value="<?php echo $invoice?>">
                <div class="data" id="bar_items" style="width:55%; margin:0">
                    <label for="item"> Select Raw material</label>
                    
                    <input type="hidden" name="material" id="material">
                    <input type="text" name="item" id="item" required placeholder="Input raw material name" onkeyup="getIceMaterial(this.value)">
                    <div id="sales_item">
                        
                    </div>
                </div>
                <div class="data" style="width:30%; margin:0;">
                    <label for="material_qty">Quantity</label>
                    <input type="number" name="material_qty" id="material_qty" value="0">
                </div>
            </div>
            <div class="inputs">
                <div class="data" id="bar_items" style="width:100%; margin:0">
                    <label for="item"> Select products</label>
                    
                    <input type="text" name="product" id="product" required placeholder="Input products" onkeyup="getIceProduct(this.value)">
                    <div id="raw_item">
                        
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
