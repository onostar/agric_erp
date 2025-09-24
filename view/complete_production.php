<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
?>

<div id="produce" class="displays">
    <?php
        //get invoice
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
            $_SESSION['product_invoice'] = $invoice;
            //get invoice details
            $get_products = new selects();
            $rows = $get_products->fetch_details_cond('production', 'product_number', $invoice);
            foreach($rows as $row){
                $store = $row->store;
                $product = $row->product;
                $quantity = $row->product_qty;
            }
            //get product name
            $get_name = new selects();
            $names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $product);
            $product_name = $names->item_name;
            //get store name
            $get_store_name = new selects();
            $store_names = $get_store_name->fetch_details_group('stores', 'store', 'store_id', $store);

        }
    ?>
    <button class="page_navs" id="back" onclick="showPage('pending_production.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--otherColor); text-align:left!important;" >Complete production -> <?php echo $invoice?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <input type="hidden" name="product_num" id="product_num" value="<?php echo $invoice?>">
                <div class="data" id="bar_items" style="width:55%; margin:0">
                    <label for="item">Product</label>
                    
                    <input type="hidden" name="product" id="product" value="<?php echo $product?>" readonly>
                    <input type="text" name="item" id="item" value="<?php echo $product_name?>" readonly>
                    <div id="sales_item">
                        
                    </div>
                </div>
                <div class="data" style="width:30%; margin:0;">
                    <label for="product_qty">Quantity</label>
                    <input type="number" name="product_qty" id="product_qty" value="<?php echo $quantity?>" readonly>
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
    <div class="stocked_in" style="width:100%;">
        <?php include "../controller/raw_material_details.php"?> 
    </div>
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>