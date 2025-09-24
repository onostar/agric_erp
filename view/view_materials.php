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
    <button class="page_navs" id="back" style="background:brown; padding:5px" onclick="showPage('production_report.php')">close <i class="fas fa-close"></i></button>
    <div class="add_user_form" style="width:50%; margin:0;box-shadow:none;">
        <h3 style="background:var(--otherColor); text-align:left!important;" >Raw Materials used for '<?php echo strtoupper($product_name)?>' production</h3>
    </div>
    <div class="displays allResults" id="stocked_items" style="width:50%!important;margin:0!important;">
        <table id="stock_items_table" class="searchTable">
            <thead>
                <tr style="background:var(--tertiaryColor)">
                    <td>S/N</td>
                    <td>Item name</td>
                    <td>Quantity(kg)</td>
                    <td>Unit cost</td>
                    <td>Total</td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_items = new selects();
                    $details = $get_items->fetch_details_2cond('production', 'product', 'product_number', $product, $invoice);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:var(--moreClor);">
                        <?php
                            //get category name
                            $get_item_name = new selects();
                            $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->raw_material);
                            echo $item_name->item_name;
                        ?>
                    </td>
                    <td style="text-align:center; color:green"><?php echo $detail->raw_quantity?></td>
                    <td>
                        <?php
                            echo "₦".number_format($detail->unit_cost, 2);
                        ?>
                    </td>
                    <td>
                        <?php
                            //get total cost
                            $total_cost = $detail->raw_quantity * $detail->unit_cost;
                            echo "₦".number_format($total_cost, 2);
                        ?>
                    </td>
                    
                </tr>
                
                <?php $n++; endforeach;}?>
            </tbody>
        </table>


        <?php
            if(gettype($details) == "string"){
                echo "<p class='no_result'>'$details'</p>";
            }

        ?>
        
    </div>
</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>