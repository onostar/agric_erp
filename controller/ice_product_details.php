<div class="displays allResults" id="stocked_items" style="width:100%!important;margin:0!important;">
    <h2>Products made from invoice <?php echo $invoice?></h2>
    <table id="stock_items_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <!-- <td>Unit cost</td> -->
                <!-- <td>Total</td> -->
                <td></td>
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
                       /*  $get_cost = new selects();
                        $costss = $get_cost->fetch_details_group('inventory', 'cost_price', 'item', $detail->raw_material);
                        echo "₦".number_format($costss->cost_price, 2); */
                    ?>
                </td> -->
                <!-- <td>
                    <?php
                        //get total cost
                        /* $get_tcost = new selects();
                        $costsss = $get_tcost->fetch_details_group('inventory', 'cost_price', 'item', $detail->raw_material);
                        $total_cost = $detail->raw_quantity * $costsss->cost_price;
                        echo "₦".number_format($total_cost, 2); */
                    ?>
                </td> -->
                <td>
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete item" onclick="deleteIceProduct('<?php echo $detail->product_id?>', <?php echo $detail->product?>)"><i class="fas fa-trash"></i></a>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        if(gettype($details) == "array"){
    ?>

    <div class="close_stockin" style="padding:10px;margin:10px 0;">
        <button onclick="postIceProduction('<?php echo $invoice?>')" style="background:green; padding:8px; border-radius:20px;box-shadow:2px 2px 2px #222">Post product <i class="fas fa-file-upload"></i></button>
    </div>
    <?php }?>
</div>