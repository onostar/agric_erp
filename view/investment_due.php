<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management" style="width:100%!important">
    
<div class="displays allResults new_data" id="revenue_report">
    <h2>Concentrate Investment due for payment</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Invoices Due')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Started</td>
                <td>Client</td>
                <td>Inv. No.</td>
                <td>Currency</td>
                <td>Units</td>
                <td>Amount</td>
                <!-- <td>Value in USD</td> -->
                <td>Amount Paid</td>
                <td>Amount Due</td>
                <td>Due Date</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_overdue_investment();
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo date("d-M-Y", strtotime($detail->start_date))?></td>
                <td>
                    <?php
                        //get client
                        $cls = $get_details->fetch_details_cond('customers', 'customer_id', $detail->customer);
                        foreach($cls as $cl){
                            $client = $cl->customer;
                        }
                        echo $client;
                    ?>
                </td>
                <td style="color:var(--primaryColor)"><?php echo "DAV/CON/00$detail->investment_id"?></td>
                <td>
                    <?php
                        echo $detail->currency;
                    ?>
                </td>
                <td><?php echo $detail->units?> unit(s)</td>
                <?php
                    if($detail->currency == "Dollar"){
                ?>
                <td style="color:var(--otherColor)"><?php echo "$".number_format($detail->amount, 2);?></td>
                <?php }else{?>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->amount, 2);?></td>
                <?php }?>
                <!-- <td style="color:var(--otherColor)"><?php echo "$".number_format($detail->total_in_dollar, 2);?></td> -->
                <td style="color:green">
                    <?php
                         //total paid
                       $paids = $get_details->fetch_sum_single('investment_payments', 'amount', 'investment', $detail->investment_id);
                       if(is_array($paids)){
                           foreach($paids as $paid){
                               $total_paid = $paid->total;
                           }
                        }else{
                            $total_paid = 0;
                        }
                        if($detail->currency == "Dollar"){
                            echo "$".number_format($total_paid, 2);
                        }else{
                            echo "₦".number_format($total_paid, 2);
                        }
                    ?>
                </td>
                <td style="color:red">
                    <?php 
                       
                        $debt = $detail->amount - $total_paid;
                        if($detail->currency == "Dollar"){
                            echo "$".number_format($debt, 2);
                        }else{
                            echo "₦".number_format($debt, 2);
                        }
                    ?>
                </td>
                <td style="color:red"><?php echo date("d-M-Y", strtotime($detail->due_date))?></td>
                <td>
                   
                    <a href="javascript:void(0)"  onclick="showPage('view_overdue_investment.php?investment=<?php echo $detail->investment_id?>&customer=<?php echo $detail->customer?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="View details">View <i class="fas fa-eye"></i></a>
                    
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