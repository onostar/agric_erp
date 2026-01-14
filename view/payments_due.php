<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management" style="width:100%!important">
    <!-- <div class="select_date">
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_invoices.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div> -->
<div class="displays allResults new_data" id="revenue_report">
    <h2>Field Purchase due for payment</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Invoices Due')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Client</td>
                <td>Field</td>
                <td>Amount Due</td>
                <td>Amount Paid</td>
                <td>Balance</td>
                <td>Due Date</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_due_payments('field_payment_schedule', 'due_date',$store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
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
                        //get field details
                        $lns = $get_customer->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        //get product name
                        /* $prds = $get_customer->fetch_details_group('loan_products', 'product', 'product_id', $lns->product); */
                        echo $lns->field_name;
                    ?>
                </td>
                <td style="color:red">
                    <?php
                        echo "₦".number_format($detail->amount_due, 2);
                    ?>
                </td>
                <td style="color:green">
                    <?php
                        echo "₦".number_format($detail->amount_paid, 2);
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php
                        echo "₦".number_format(($detail->amount_due - $detail->amount_paid), 2);
                    ?>
                </td>
                
                <td style="color:var(--primaryColor)"><?php echo date("d-M-Y", strtotime($detail->due_date));?></td>
                <td>
                    <a href="javascript:void(0);" title="View details" style="padding:5px; background:var(--otherColor);color:#fff; border-radius:15px;" onclick="showPage('view_active_purchase.php?assigned=<?php echo $detail->assigned_id?>')">View <i class="fas fa-eye"></i></a>
                    <a href="javascript:void(0);" title="Post Payment" style="padding:5px; background:var(--tertiaryColor);color:#fff; border-radius:15px;" onclick="showPage('field_payment.php?schedule=<?php echo $detail->repayment_id?>&customer=<?php echo $detail->customer?>')">Make Payment <i class="fas fa-hand-holding-dollar"></i></a>
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
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_curdategreater2Con('field_payment_schedule', 'amount_paid', 'due_date', 'store', $store, 'payment_status', 0);
        foreach($amounts as $amount){
            $paid_amount = $amount->total;
            
        }
        $dues = $get_total->fetch_sum_curdategreater2Con('field_payment_schedule', 'amount_due', 'due_date', 'store', $store, 'payment_status', 0);
        foreach($dues as $due){
            $due_amount = $due->total;
            
        }
        $total_due = $due_amount - $paid_amount;
        
            echo "<p class='sum_amount' style='background:green'><strong>Total</strong>: ₦".number_format($total_due, 2)."</p>";
            
        
    ?>
           
        </div>
            
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>