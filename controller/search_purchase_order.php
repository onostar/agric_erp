<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_users = new selects();
    $details = $get_users->fetch_details_dateGro2con('purchase_order', 'date(post_date)', $from, $to, 'store', $store, 'order_status', 1, 'invoice');
    $n = 1;  
?>
<h2>Purchase Order Report between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchPurchase" placeholder="Enter keyword" onkeyup="searchData(this.value)">
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
                <td>Post Date</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

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
            <?php $n++; }?>
        </tbody>
    </table>
<?php
    }else{
        echo "<p class='no_result'>'$details'</p>";
    }
   
    // get sum
    $amounts = $get_users->fetch_sum_2col2date1con('purchase_order', 'cost_price', 'quantity', 'date(post_date)', $from, $to, 'store', $store);
    foreach($amounts as $amount){
        echo "<p class='total_amount' style='color:green; text-align:right'>Total Goods Purchased: ₦".number_format($amount->total, 2)."</p>";
    }
     
?>
