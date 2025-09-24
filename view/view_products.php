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
            $rows = $get_products->fetch_details_cond('ice_cream', 'product_number', $invoice);
            foreach($rows as $row){
                $store = $row->store;
                $material = $row->raw_material;
                $quantity = $row->raw_quantity;
            }
            //get product name
            $get_name = new selects();
            $names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $material);
            $material_name = $names->item_name;
            //get store name
            $get_store_name = new selects();
            $store_names = $get_store_name->fetch_details_group('stores', 'store', 'store_id', $store);

        }
    ?>
    <button class="page_navs" id="back" style="background:brown; padding:5px" onclick="showPage('ice_cream_production.php')">close <i class="fas fa-close"></i></button>
    <div class="add_user_form" style="width:50%; margin:0;box-shadow:none;">
        <h3 style="background:var(--otherColor); text-align:left!important;" >Products made from '<?php echo strtoupper($material_name)?>'</h3>
    </div>
    <div class="displays allResults" id="stocked_items" style="width:50%!important;margin:0!important;">
        <table id="stock_items_table" class="searchTable">
            <thead>
                <tr style="background:var(--tertiaryColor)">
                    <td>S/N</td>
                    <td>Item name</td>
                    <td>Quantity</td>
                    <!-- <td>Unit cost</td> -->
                    <!-- <td>Total</td> -->
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_items = new selects();
                    $details = $get_items->fetch_details_2cond('ice_cream', 'raw_material', 'product_number', $material, $invoice);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:var(--moreClor);">
                        <?php
                            //get category name
                            $get_item_name = new selects();
                            $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->product);
                            echo $item_name->item_name;
                        ?>
                    </td>
                    <td style="text-align:center; color:green"><?php echo $detail->product_qty?></td>
                    <!-- <td>
                        <?php
                            //get cost
                            $get_cost = new selects();
                            $costss = $get_cost->fetch_details_group('inventory', 'cost_price', 'item', $detail->raw_material);
                            echo "₦".number_format($costss->cost_price, 2);
                        ?>
                    </td> -->
                    <!-- <td>
                        <?php
                            //get total cost
                            $get_tcost = new selects();
                            $costsss = $get_tcost->fetch_details_group('inventory', 'cost_price', 'item', $detail->raw_material);
                            $total_cost = $detail->raw_quantity * $costsss->cost_price;
                            echo "₦".number_format($total_cost, 2);
                        ?>
                    </td> -->
                    
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