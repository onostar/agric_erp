<?php
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_GET['vendor'])){
        $customer = $_GET['vendor'];
    }
    $from = $_SESSION['fromDate'];
    $to = $_SESSION['toDate'];
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get customer details
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_cond('vendors', 'vendor_id', $customer);
    foreach($rows as $row){
        $name = $row->vendor;
        $contact = $row->contact_person;
        $phone = $row->phone;
        $email = $row->email_address;
        $joined = $row->created_date;
        $debt = $row->balance;
        $acn = $row->account_no;
    }
    $get_balance = new selects();
    $bals = $get_balance->fetch_vendor_balance($acn);
    if(gettype($bals) == 'array'){
        foreach($bals as $bal){
            $balance = $bal->balance;
        }
    }
?>
<!-- customer info -->
<div class="close_btn">
    <a href="javascript:void(0)" title="Close form" onclick="showPage('../view/vendor_statement.php');" class="close_form">Close <i class="fas fa-close"></i></a>
</div>
<div class="customer_info" class="allResults">
    <h3 style="background:var(--tertiaryColor)">Statment for <?php echo $name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h3>
    <div class="demography">
        <div class="demo_block">
            <h4><i class="fas fa-id-card"></i> Name:</h4>
            <p><?php echo $name?></p>
        </div>
        <div class="demo_block">
            <h4><i class="fas fa-map"></i> Contact person:</h4>
            <p><?php echo $contact?></p>
        </div>
        <div class="demo_block">
            <h4><i class="fas fa-phone-square"></i> Phone numbers:</h4>
            <p><?php echo $phone?></p>
        </div>
        <div class="demo_block">
            <h4><i class="fas fa-envelope"></i> Email:</h4>
            <p><?php echo $email?></p>
        </div>
        <div class="demo_block">
            <h4><i class="fas fa-calendar"></i> Registered:</h4>
            <p><?php echo date("jS M, Y", strtotime($joined))?></p>
        </div>
        <div class="demo_block" style="color:green">
            <h4 style="color:green"><i class="fas fa-piggy-bank"></i> Amount due:</h4>
            <p><?php echo "₦".number_format($balance, 2)?></p>
        </div>
    </div>
    <h3 style="background:red; text-align:center; color:#fff; padding:10px;margin:0;">Transactions</h3>
    <div class="transactions">
        <div class="all_credit allResults">
            <h3 style="background:var(--otherColor); color:#fff">All purchase transaction</h3>
            <table id="data_table" class="searchTable">
                <thead>
                <tr style="background:var(--primaryColor)">
                        <td>S/N</td>
                        <td>Purchase Date</td>
                        <td>Invoice</td>
                        <td>Items</td>
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //get transaction history
                        $get_transactions = new selects();
                        $details = $get_transactions->fetch_details_dateGro1con('purchases', 'date(post_date)', $from, $to, 'vendor', $customer, 'invoice');
                        $n = 1;
                        if(gettype($details) === 'array'){
                        foreach($details as $detail){

                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->purchase_date));?></td>
                        <td><a style="color:green" href="javascript:void(0)" title="View invoice details" onclick="viewVendorInvoice('<?php echo $detail->invoice?>', '<?php echo $customer?>')"><?php echo $detail->invoice?></a></td>  
                        <td style="text-align:center">
                            <?php
                                //get items in invoice;
                                $get_items = new selects();
                                $items = $get_items->fetch_count_cond('purchases', 'invoice', $detail->invoice);
                                echo $items;
                            ?>
                        </td>   
                        <td>
                            <?php 
                                
                                    $get_sum = new selects();
                                    $sums = $get_sum->fetch_sum_2colCond('purchases', 'cost_price', 'quantity',  'invoice', $detail->invoice);
                                    foreach($sums as $sum){
                                        echo "₦".number_format($sum->total, 2);

                                    }
                                
                                
                            ?>
                        </td>
                        
                    </tr>
                    <?php $n++; }}?>
                </tbody>
            </table>
            <?php
                if(gettype($details) == "string"){
                    echo "<p class='no_result'>'$details'</p>";
                }
                // get sum
                $get_total = new selects();
                $amounts = $get_total->fetch_sum_2col2date1con('purchases', 'quantity', 'cost_price', 'date(post_date)', $from, $to, 'vendor', $customer);
                foreach($amounts as $amount){
                    $paid_amount = $amount->total;
                }
                
                    echo "<p class='total_amount' style='color:green'>Invoice Total: ₦".number_format($paid_amount, 2)."</p>";
                    
                //get total logistics
                $get_log = new selects();
                $logs = $get_log->fetch_sum_2date1CondGr('purchases', 'waybill', 'vendor', 'date(post_date)', $from, $to, $customer, 'invoice');
                foreach($logs as $log){
                    $logistics = $log->total;
                }
                echo "<p class='total_amount' style='color:#222'>Total Logistics: ₦".number_format($logistics, 2)."</p>";
            ?>
        </div>
        <div class="all_credit allResults">
            <h3 style="background:var(--primaryColor); color:#fff">Payments</h3>
            <table id="data_table" class="searchTable">
                <thead>
                <tr style="background:var(--primaryColor)">
                        <td>S/N</td>
                        <td>Date</td>
                        <td>Mode</td>
                        <!-- <td>Items</td> -->
                        <td>Amount</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //get deposit history
                        $get_transactions = new selects();
                        $details = $get_transactions->fetch_details_2date1Con1Neg('purchase_payments', 'date(post_date)', $from, $to, 'vendor', $customer, 'payment_mode', 'Credit');
                        $n = 1;
                        if(gettype($details) === 'array'){
                        foreach($details as $detail){

                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                        <td><?php echo $detail->payment_mode?></td>  
                        <td>
                            <?php echo "₦".number_format($detail->amount_paid, 2);

                            ?>
                        </td>
                        
                    </tr>
                    <?php $n++; }}?>
                </tbody>
            </table>
            <?php
                if(gettype($details) == "string"){
                    echo "<p class='no_result'>'$details'</p>";
                }
                // get sum of deposits
                $get_credits = new selects();
                $credits = $get_credits->fetch_sum_double1Neg('purchase_payments', 'amount_paid', 'vendor', $customer, 'payment_mode', 'Credit');
                foreach($credits as $credit){
                    $deposits = $credit->total;
                }
                /* // get sum of deposit
                $get_deposits = new selects();
                $debits = $get_total->fetch_sum_2date2Cond('customer_trail', 'amount', 'date(post_date)', 'customer', 'description', $from, $to, $customer, 'deposit');
                foreach($debits as $debit){
                    $deposits = $debit->total;
                } */
                /* $total_due = $debt;
                    if($total_due > 0){ */
                        echo "<p class='total_amount' style='color:green;font-size:1rem;'>Total Deposit: ₦".number_format($deposits, 2)."</p>";    
                    // }else{
                        echo "<p class='total_amount' style='color:red;font-size:1rem;'>Amount due: ₦".number_format($balance, 2)."</p>";

                    // }
                
            ?>
        </div>
    </div>
    <div id="customer_invoices">
        
    </div>
</div>