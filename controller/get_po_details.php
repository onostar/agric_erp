<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        if(isset($_GET['item'])){
            $item = htmlspecialchars($_GET['item']);
            $invoice = htmlspecialchars($_GET['invoice']);
            $vendor = htmlspecialchars($_GET['vendor']);
            $date = "05-09-2026";
    
    // $invoice = $_SESSION['purchase_invoice'];
    // $vendor = $_SESSION['vendor'];
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
    ?>
    <div class="add_user_form" style="width:60%!important; margin:0!important">
        <h3 style="background:var(--tertiaryColor); text-align:left;">Raise Order for <?php echo strtoupper($row->item_name);?></h3>
        <section class="addUserForm" style="text-align:left!important;">
            <div class="inputs" style="flex-wrap:wrap;gap:1rem;">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>" required>
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>" required>
                    <input type="hidden" name="invoice_number" id="invoice_number" value="<?php echo $invoice?>" required>
                    <input type="hidden" name="supplier" id="supplier" value="<?php echo $vendor?>" required>
                    <input type="hidden" name="item_id" id="item_id" value="<?php echo $row->item_id?>" required>
                    <input type="hidden" name="item_type" id="item_type" value="<?php echo $row->item_type?>" required>
                <div class="data" style="width:30%; margin:5px;">
                    <label for="quantity">Quantity(kg)</label>
                    <input type="number" name="quantity" id="quantity">
                </div>
                <div class="data" style="width:30%; margin:5px;">
                    <label for="cost_price">Cost price (NGN)</label>
                    <input type="text" name="cost_price" id="cost_price" value="<?php echo $row->cost_price?>">
                </div>
                <!-- <div class="data" style="width:20%; margin:5px;">
                    <label for="sales_price">Retail price (NGN)</label>
                    <input type="text" name="sales_price" id="sales_price" value="<?php echo $row->sales_price?>">
                </div>
                <div class="data" style="width:20%; margin:5px;">
                    <label for="sales_price">Retail Pack price (NGN)</label>
                    <input type="text" name="pack_price" id="pack_price" value="<?php echo $row->pack_price?>">
                </div>
                <div class="data" style="width:20%; margin:5px;">
                    <label for="sales_price">Wholesale price (NGN)</label>
                    <input type="text" name="wholesale_price" id="wholesale_price" value="<?php echo $row->wholesale?>">
                </div>
                <div class="data" style="width:20%; margin:5px;">
                    <label for="pack_price">Wholesale Pack (NGN)</label>
                    <input type="text" name="wholesale_pack" id="wholesale_pack" value="<?php echo $row->wholesale_pack?>">
                </div>
                <div class="data" style="width:20%; margin:5px;">
                    <label for="sales_price">Pack size</label>
                    <input type="text" name="pack_size" id="pack_size" value="<?php echo $row->pack_size?>">
                </div> -->
                <!-- <div class="data" style="width:20%; margin:5px;">
                    <label for="expiration_date">Expiration date</label>
                    <input type="date" name="expiration_date" id="expiration_date" required>
                </div> -->
                <div class="data" style="width:auto; margin:5px;">
                    <button type="submit" id="stockin" name="stockin" title="stockin item" onclick="raisePO()"><i class="fas fa-check"></i></button>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }
    }else{
        echo "Your session has expired, please login again to continue";
        exit();
        // header("Location: ../index.php");
    } 
?>