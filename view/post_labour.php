

<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="post_purchase" class="displays management" style="width:100%!important">
<div class="displays allResults new_data" id="revenue_report">
    <h2>Post Pending Labour Cost Registered</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Labour Cost report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Vendor</td>
                <td>Items</td>
                <td>Amount due</td>
                <!-- <td>Payment Mode</td> -->
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateGro2con('purchases', 'date(post_date)', 'store', $store, 'purchase_status', 0, 'invoice');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="showPage('invoice_details.php?payment_id=<?php echo $detail->payment_id?>')"><?php echo $detail->invoice?></a></td>
                <td>
                    <?php
                        $get_guest = new selects();
                        $rows = $get_guest->fetch_details_group('vendors', 'vendor', 'vendor_id', $detail->vendor);
                        echo $rows->vendor;
                    ?>
                </td>
                <td style="color:var(--otherColor);text-align:center">
                    <?php 
                        //get items in invoice;
                        $get_items = new selects();
                        $items = $get_items->fetch_count_cond('purchases', 'invoice', $detail->invoice);
                        echo $items;
                    ?>
                </td>
                
                <td style="color:red">
                    <?php 

                        //get sum of invoice discount
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_sum_2colCond('purchases', 'cost_price', 'quantity', 'invoice', $detail->invoice);
                        foreach($sums as $sum){
                            echo "₦".number_format($sum->total, 2);

                        }
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:#fff;background:var(--otherColor); padding:5px; border-radius:10px" href="javascript:void(0)" title="View details" onclick="showPage('purchase_details.php?invoice=<?php echo $detail->invoice?>&vendor=<?php echo $detail->vendor?>')">View <i class="fas fa-eye"></i></a>
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
        <?php
        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_2colCurDate1Con('purchases', 'cost_price', 'quantity', 'post_date', 'store', $store);
        foreach($amounts as $amount){
            $paid_amount = $amount->total;
            
        }
        
            echo "<p class='sum_amount'; margin-left:100px;color:green;text-decoration:underline'><strong>Total</strong>: ₦".number_format($paid_amount, 2)."</p>";
    ?>
            <!-- </div> -->
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>