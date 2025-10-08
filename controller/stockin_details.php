<div class="displays allResults" id="stocked_items" style="width:100%!important; margin:0!important">
    <h2>Items stocked in with invoice <?php echo $invoice?></h2>
    <table id="stock_items_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <td>Unit cost</td>
                <!-- <td>Unit sales</td>
                <td>Expiration</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_2cond('purchases', 'vendor', 'invoice', $supplier, $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        //get category name
                        $get_item_name = new selects();
                        $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $item_name->item_name;
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->quantity?></td>
                <td>
                    <?php 
                        echo "₦".number_format($detail->cost_price, 2);
                    ?>
                </td>
                <!-- <td>
                    <?php 
                        echo "₦".number_format($detail->sales_price, 2);
                    ?>
                </td>
                <td><?php echo $detail->expiration_date?></td> -->
                <td>
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete purchase" onclick="deletePurchase('<?php echo $detail->purchase_id?>', <?php echo $detail->item?>)"><i class="fas fa-trash"></i></a>
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }

        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_2con('purchases', 'cost_price', 'quantity', 'vendor', 'invoice', $supplier, $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // $total_worth = $total_amount * $total_qty;
        echo "<p class='total_amount' style='color:red;float:right'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
    ?>
    <!-- <div class="close_stockin">
        <button onclick="showPage('stockin_purchase.php')" style="background:red; padding:8px; border-radius:5px;">Close stockin <i class="fas fa-power-off"></i></button>
    </div> -->
    <div class="close_stockin add_user_form" style="width:50%; margin:0;">
        <section class="addUserForm">
            <div class="inputs" style="display:flex;flex-wrap:wrap">
                <input type="hidden" name="suppliers" id="suppliers" value="<?php echo $supplier?>">
                <input type="hidden" name="sales_invoice" id="sales_invoice" value="<?php echo $invoice?>">
                <div class="data">
                    <label for="" style="color:var(--tertiaryColor)">Waybill/Frieght</label>
                    <input type="number" name="waybill" id="waybill" value="0">
                </div>
                <div class="data">
                    <button onclick="postStockin()" style="background:green; padding:8px; border-radius:5px;font-size:.9rem;">Post Stockin <i class="fas fa-power-off"></i></button>
                </div>
            </div>
        </section>
    </div>
</div>