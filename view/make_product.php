<div id="produce" class="displays">

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
        <h3 style="background:var(--primaryColor); color:#fff; text-align:left!important;">Make a new product</h3>

        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <input type="hidden" name="product_num" id="product_num" value="<?php echo $invoice?>">
                <div class="data" id="bar_items" style="width:55%; margin:0">
                    <label for="item"> Select Product</label>
                    
                    <input type="hidden" name="product" id="product">
                    <input type="search" name="item" id="item" required placeholder="Input product name" onkeyup="getProduct(this.value)">
                    <div id="sales_item">
                        
                    </div>
                </div>
                <div class="data" style="width:30%; margin:0;">
                    <label for="product_qty">Quantity (KG)</label>
                    <input type="number" name="product_qty" id="product_qty" value="0">
                </div>
            </div>
            <div class="inputs">
                <div class="data" id="bar_items" style="width:100%; margin:0">
                    <label for="item"> Select raw materials</label>
                    
                    <input type="text" name="item_raw" id="item_raw" required placeholder="Input raw material" onkeyup="getRawItem(this.value)">
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
