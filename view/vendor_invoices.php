<div id="pay_debts" class="displays" style="width:80%!important;margin:0 20px!important">
<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    
?>

<?php
    if(isset($_GET['vendor'])){
        $vendor = $_GET['vendor'];
       
        //get vendor details
        $get_customer = new selects();
        $custs = $get_customer->fetch_details_cond('vendors', 'vendor_id', $vendor);
        foreach($custs as $cust){
            $client = $cust->vendor;
            // $balance = $cust->balance;
        }

?>
    <div class="top_view">
        <!-- <button class="page_navs" id="back" onclick="showPage('pay_debt.php')"><i class="fas fa-angle-double-left"></i> Back</button> -->
        <button class="page_navs" id="back" onclick="showPage('post_vendor_payments.php')"><i class="fas fa-angle-double-left"></i> Back</button>

        <?php
            //wallet balance
            // echo "<p style='color:green;font-size:1.1rem;'>Wallet Balance: ₦".number_format($balance, 2)."</p>";
            
        ?>
    </div>

    <div id="debt_payment" class="all_details">
        <!-- <div class="info"></div> -->
            <div class="displays allResults" id="payment_det">
            
                <div class="payment_details">
                    <h3 style="width:100%; background:var(--otherColor); color:#fff;padding:5px;font-size:1rem">Showing all  invoices for <?php echo $client?></h3>
                    <table id="data_table" class="searchTable">
                    <thead>
                    <tr style="background:var(--primaryColor)">
                            <td>S/N</td>
                            <td>Invoice</td>
                            <td>Items</td>
                            <td>Invoice Amount</td>
                            <td>Mode</td>
                            <td>Deposit</td>
                            <td>Date</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            //get transaction history
                            $get_transactions = new selects();
                            $details = $get_transactions->fetch_details_2condGroup('purchases', 'vendor', 'purchase_status', $vendor, 1, 'invoice');
                            $n = 1;
                            if(gettype($details) === 'array'){
                            foreach($details as $detail){

                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="viewVendorInvoice('<?php echo $detail->invoice?>', '<?php echo $detail->vendor?>')"><?php echo $detail->invoice?></a></td>  
                            <td style="text-align:center">
                                <?php
                                    //get items in invoice;();
                                    $items = $get_transactions->fetch_count_2cond('purchases', 'invoice', $detail->invoice, 'vendor', $detail->vendor);
                                    echo $items;
                                ?>
                            </td>   
                            <td>
                                <?php 
                                    //get sum of invoice
                                    $sums = $get_transactions->fetch_sum_2col2Cond('purchases', 'cost_price', 'quantity', 'invoice', $detail->invoice, 'vendor', $detail->vendor);
                                    foreach($sums as $sum){
                                        $invoice_total = $sum->total;
                                        
                                    }
                                    $grand_total = $invoice_total;
                                    echo "₦".number_format($grand_total, 2);
                                ?>
                            </td>
                            <td>
                                <?php 
                                    //get payment mode
                                    $modes = $get_transactions->fetch_details_2cond('purchase_payments', 'vendor', 'invoice', $detail->vendor, $detail->invoice);
                                    if(is_array($modes)){
                                        foreach($modes as $mode){
                                            echo $mode->payment_mode;
                                        }
                                    }else{
                                        echo "<span style='color:red'>Not Posted</span>";
                                    }
                                ?>
                            </td>
                            <td style="color:green">
                                <?php 
                                    //get sum of amount paid
                                    $paids = $get_transactions->fetch_details_2cond('purchase_payments',  'vendor', 'invoice', $detail->vendor, $detail->invoice);
                                    if(is_array($paids)){
                                        foreach($paids as $paid){
                                            $amount_paid = $paid->amount_paid;
                                        }
                                    }else{
                                        $amount_paid = 0;
                                    }
                                    
                                    echo "₦".number_format($amount_paid, 2);
                                ?>
                            </td>
                           
                            <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                        </tr>
                        <?php $n++; }}?>
                    </tbody>
                </table>
                <?php
                    if(gettype($details) == "string"){
                        echo "<p class='no_result'>'$details'</p>";
                    }
                    //get invoice total
                    $details = $get_transactions->fetch_sum_2col2Cond('purchases', 'cost_price', 'quantity', 'vendor', $vendor, 'purchase_status', 1);
                    foreach($details as $detail){
                        $invoice_amount = $detail->total;
                        echo "<p class='total_amount' style='color:#222; font-size:1rem;'>Total Invoice Amount: ₦".number_format($invoice_amount, 2)."</p>";
                    }
                    // get sum of total payment
                    $amounts = $get_transactions->fetch_sum_single('vendor_payments', 'amount', 'vendor', $vendor);
                    if(gettype($amounts) === 'array'){
                        foreach($amounts as $amount){
                            $total_paid = $amount->total;
                        }
                    }else{
                        $total_paid = 0;
                    }
                    echo "<p class='total_amount' style='color:green; font-size:1rem;'>Total Paid: ₦".number_format($total_paid, 2)."</p>";
                    $balance = $invoice_amount - $total_paid;
                    if($balance > 0){
                        echo "<p class='total_amount' style='color:red; font-size:1rem;'>Total Due: ₦".number_format($balance, 2)."</p>";
                    }
                ?>
                </div>
                
        <div id="customer_invoices">

        </div>
    </div>
<?php
            }
        
   
?>
</div>
