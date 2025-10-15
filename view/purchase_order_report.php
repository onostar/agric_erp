<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    $store = $_SESSION['store_id'];

?>
<div id="purchaseReport" class="displays management" style="width:100%!important">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_purchase_order.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data">
    <h2>Purchase Register for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Purchase Order report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>PO Number</td>
                <td>Vendor</td>
                <td>Total items</td>
                <td>Amount</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateGro2con('purchase_order', 'post_date', 'store', $store, 'order_status', 1, 'invoice');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->invoice?></td>
                <td>
                    <?php
                        $rows = $get_users->fetch_details_group('vendors', 'vendor', 'vendor_id', $detail->vendor);
                        echo $rows->vendor;
                    ?>
                </td>
                <td style="text-align:center">
                    <?php
                         //get total items with that invoice
                        $sums = $get_users->fetch_count_cond('purchase_order', 'invoice', $detail->invoice);
                        echo $sums;
                    ?>
                </td>
                <td style="text-align:center; color:green;">
                    <?php 
                        //get sum
                        $ttls = $get_users->fetch_sum_2col2Cond('purchase_order', 'quantity','cost_price', 'invoice', $detail->invoice, 'vendor', $detail->vendor);
                        if(is_array($ttls)){
                            foreach($ttls as $ttl){
                                $total_cost = $ttl->total;
                            }
                        }else{
                            $total_cost = 0;
                        }
                        echo "₦".number_format($total_cost, 2)
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $posted_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $posted_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:green; background:var(--otherColor); padding:5px; border-radius:5px; color:#fff" href="javascript:void(0)" title="View invoice details" onclick="showPage('view_purchase_order.php?invoice=<?php echo $detail->invoice?>')"> <i class="fas fa-eye"></i> View</a>
                </td>
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        $amounts = $get_users->fetch_sum_2colCurDate1Con('purchase_order', 'cost_price', 'quantity', 'date(post_date)', 'store', $store);
        foreach($amounts as $amount){
           $total_amount = $amount->total;
        }
        
        echo "<p class='total_amount' style='color:green; text-align:right;'>Total Goods Ordered: ₦".number_format($total_amount, 2)."</p>";
    ?>

</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>