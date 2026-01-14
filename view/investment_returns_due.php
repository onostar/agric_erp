<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management" style="width:100%!important">
    
<div class="displays allResults new_data" id="revenue_report">
    <h2>Investment Returns due for payment</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Invoices Due')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Client</td>
                <td>Investment No.</td>
                <td>Returns Due</td>
                <td>Returns Paid</td>
                <td>Balance</td>
                <td>Due Date</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_due_payments('investment_returns', 'due_date', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get investment details
                    $invs = $get_users->fetch_details_cond('investments', 'investment_id', $detail->investment_id);
                    foreach($invs as $inv){
                        $currency = $inv->currency;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        //get customer
                        $get_customer = new selects();
                        $custs = $get_customer->fetch_details_cond('customers', 'customer_id', $detail->customer);
                        foreach($custs as $cust){
                            echo $cust->customer;
                        }
                    ?>
                </td>
                <td>
                    <?php
                        //get investment details
                        echo "DAV/CON/00".$detail->investment_id;
                    ?>
                </td>
                <td style="color:red">
                    <?php
                        if($currency == "Dollar"){
                            echo "$".number_format($detail->amount_due, 2);
                        }else{
                            echo "₦".number_format($detail->amount_due, 2);
                        }
                    ?>
                </td>
                <td style="color:green">
                    <?php
                        if($currency == "Dollar"){
                            echo "$".number_format($detail->amount_paid, 2);
                        }else{
                            echo "₦".number_format($detail->amount_paid, 2);
                        }
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php
                        if($currency == "Dollar"){
                            echo "$".number_format(($detail->amount_due - $detail->amount_paid), 2);
                        }else{
                            echo "₦".number_format(($detail->amount_due - $detail->amount_paid), 2);
                        }
                    ?>
                </td>
                
                <td style="color:var(--primaryColor)"><?php echo date("d-M-Y", strtotime($detail->due_date));?></td>
                <td>
                    <!-- <a href="javascript:void(0);" title="View details" style="padding:5px; background:var(--otherColor);color:#fff; border-radius:15px;" onclick="showPage('view_active_loan.php?loan=<?php echo $detail->loan?>')">View <i class="fas fa-eye"></i></a> -->
                    <a href="javascript:void(0);" title="Post Payment" style="padding:5px; background:var(--tertiaryColor);color:#fff; border-radius:15px;" onclick="showPage('investment_returns_payment.php?schedule=<?php echo $detail->schedule_id?>&customer=<?php echo $detail->customer?>')">Post Payment <i class="fas fa-hand-holding-dollar"></i></a>
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
        <div class="all_modes">
   
        <?php
        // get sum
       /*  $get_total = new selects();
        $amounts = $get_total->fetch_sum_curdategreater2Con('rent_schedule', 'amount_paid', 'due_date', 'store', $store, 'payment_status', 0);
        foreach($amounts as $amount){
            $paid_amount = $amount->total;
            
        }
        $dues = $get_total->fetch_sum_curdategreater2Con('rent_schedule', 'amount_due', 'due_date', 'store', $store, 'payment_status', 0);
        foreach($dues as $due){
            $due_amount = $due->total;
            
        }
        $total_due = $due_amount - $paid_amount;
        
            echo "<p class='sum_amount' style='background:green'><strong>Total</strong>: ₦".number_format($total_due, 2)."</p>"; */
            
        
    ?>
           
        </div>
            
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>