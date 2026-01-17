<div class="displays allResults" id="stocked_items" style="width:100%!important;margin:10px!important">
    <h2>Items requested on invoice <?php echo $invoice?></h2>
    <table id="stock_items_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Quantity</td>
                <!-- <td>Unit cost</td> -->
                <!-- <td>Unit sales</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_2cond('issue_items', 'from_store', 'invoice', $store_from, $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    $get_ind = new selects();
                    $alls = $get_ind->fetch_details_cond('items', 'item_id', $detail->item);
                    foreach($alls as $all){
                        // $sales_price = $all->sales_price;
                        $itemname = $all->item_name;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);">
                    <?php
                        echo $itemname;
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->quantity?></td>
                
                <td>
                    <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete item" onclick="deleteIssued('<?php echo $detail->issue_id?>', <?php echo $detail->item?>)"><i class="fas fa-trash"></i></a>
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
        // get sum
        /* $get_total = new selects();
        $amounts = $get_total->fetch_sum_2con('issue_items', 'cost_price', 'quantity', 'from_store', 'invoice', $store_from, $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // $total_worth = $total_amount * $total_qty;
        echo "<p class='total_amount' style='color:red'>Total Cost: ₦".number_format($total_amount, 2)."</p>"; */
    ?>
    <div class="close_stockin">
        <button onclick="postIssued('<?php echo $invoice?>')" style="background:green; padding:8px; border-radius:5px;">Post Items <i class="fas fa-upload"></i></button>
    </div>
    <?php }?>
</div>