<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get store
    $get_store = new selects();
    $str = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $str->store;
    $get_account = new selects();
?>
<div id="revenueReport" class="displays management" style="width:70%!important;margin:20px!important">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date" style="width:30%">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date" style="width:30%">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_profit.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data" id="revenue_report" style="width:60%">
    <!-- <hr> -->
    <div class="search">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Income Statement for <?php echo date('Y-m-d')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <h2 style="background:var(--tertiaryColor); color:#fff; padding:10px;">Income statement for "<?php echo date("jS M, Y")?>"</h2>
    
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>Details</td>
                <td>Amount (₦)</td>
                <!-- <td>Account No.</td>
                <td>Debit</td>
                <td>Credit</td> -->
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="color:#222;text-align:left">Revenue</td>
                <td>
                    <?php
                        // get accounts
                        
                        $rows = $get_account->fetch_sum_curdate2Con('sales', 'total_amount', 'date(post_date)', 'store', $store, 'sales_status', 2);
                        foreach($rows as $row){
                            $revenue = $row->total;
                            // $cost = $row->total_cost;
                        }
                        echo number_format($revenue, 2)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Other Revenue (Gain from disposal of Asset, etc)</td>
                <td>
                    <?php
                        // get accounts
                        $rows = $get_account->fetch_sum_curdate2Con('other_income', 'amount', 'post_date', 'activity', 'gain', 'store', $store);
                        foreach($rows as $row){
                            $other_revenue = $row->total;
                            // $cost = $row->total_cost;
                        }
                        echo number_format($other_revenue, 2)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Cost of Goods Sold (COGS)</td>
                <td>
                    <?php    
                        

                        //get cost of sales
                        $costss = $get_account->fetch_sum_curdateCon('cost_of_sales', 'amount', 'date(post_date)', 'store', $store);
                        foreach($costss as $costs){
                            $cost = $costs->total;
                        }
                        /* //get waybill
                        $get_waybill = new selects();
                        $bills = $get_waybill->fetch_sum_curdate('waybills', 'waybill', 'date(post_date)');
                        foreach($bills as $bill){
                            $logistic = $bill->total;
                        } */
                        // $total_cost = $cost + $logistic;
                        echo number_format($cost, 2);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left; font-weight:bold; text-transform:uppercase">Gross Profit</td>
                <td style="font-weight:bold;">
                    <?php
                        $gross_profit = ($revenue + $other_revenue) - $cost;
                        echo number_format($gross_profit, 2);
                    ?>
                </td>
            </tr>
            <tr>
                <td colspan="2" style="color:#222;text-align:left; font-weight:bold; text-transform:uppercase">Expenses</td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Operating Expense</td>
                <td>
                    <?php    
                        //get waybill
                        $bills = $get_account->fetch_sum_curdateCon('waybills', 'waybill', 'date(post_date)', 'store', $store);
                        foreach($bills as $bill){
                            $logistic = $bill->total;
                        }
                        echo number_format($logistic, 2);
                        
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Admin Expense</td>
                <td>
                    <?php    
                        //get expense
                        $exps = $get_account->fetch_sum_curdateCon('expenses', 'amount', 'date(post_date)', 'store', $store);
                        foreach($exps as $exp){
                            $expense = $exp->total;
                        }
                        // echo number_format($expense, 2);

                        //get bank charges
                        $char = $get_account->fetch_sum_curdate2Con('finance_cost', 'amount', 'date(post_date)', 'store', $store, 'trans_type', 'Bank Charges');
                        foreach($char as $cha){
                            $charges = $cha->total;
                        }
                        $total_expense = $charges + $expense;
                        echo number_format($total_expense, 2);

                    ?>
                </td>
            </tr>
            <!-- <tr>
                <td style="color:#222;text-align:left">Bank Charges</td>
                <td>
                    <?php    
                        //get expense
                        $char = $get_account->fetch_sum_curdate2Con('finance_cost', 'amount', 'date(post_date)', 'store', $store, 'trans_type', 'Bank Charges');
                        foreach($char as $cha){
                            $charges = $cha->total;
                        }
                        echo number_format($charges, 2)
                    ?>
                </td>
            </tr> -->
            <tr>
                <td style="color:#222;text-align:left">Loss from Asset Disposal</td>
                <td>
                    <?php    
                        //get expense
                        $other_loss = $get_account->fetch_sum_curdate2Con('other_income', 'amount', 'post_date', 'activity', 'loss', 'store', $store);
                        foreach($other_loss as $row){
                            $loss = $row->total;
                            // $cost = $row->total_cost;
                        }
                        echo number_format($loss, 2)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Other Finance Cost (Loan Fees)</td>
                <td>
                    <?php    
                        //get expense
                        $others = $get_account->fetch_sum_curdate2Con('finance_cost', 'amount', 'date(post_date)', 'store', $store, 'trans_type', 'Loan Fee');
                        foreach($others as $oth){
                            $finance_cost = $oth->total;
                        }
                        echo number_format($finance_cost, 2)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Depreciation</td>
                <td>
                    <?php    
                        //get depreciation
                        $others = $get_account->fetch_sum_curdateCon('depreciation', 'amount', 'date(post_date)', 'store', $store);
                        foreach($others as $oth){
                            $depreciation = $oth->total;
                        }
                        echo number_format($depreciation, 2)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left; font-weight:bold; text-transform:uppercase">Total Expenses</td>
                <td style="font-weight:bold">
                    <?php
                        $exp_total = $logistic + $total_expense + $finance_cost + $loss + $depreciation;
                        echo number_format($exp_total, 2);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left; font-weight:bold; text-transform:uppercase">Earnings Before tax</td>
                <td style="font-weight:bold;">
                    <?php
                        $earnings = $gross_profit - $exp_total;
                        echo number_format($earnings, 2);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left">Taxes</td>
                <td>
                    <?php    
                        //get expense
                        $others = $get_account->fetch_sum_curdate2Con('finance_cost', 'amount', 'date(post_date)', 'store', $store, 'trans_type', 'Tax');
                        foreach($others as $oth){
                            $tax = $oth->total;
                        }
                        echo number_format($tax, 2)
                    ?>
                </td>
            </tr>
            <tr>
                <td style="color:#222;text-align:left; font-weight:bold; text-transform:uppercase">Net Earnings</td>
                <td style="font-weight:bold;">
                    <?php
                        $net_earnings = $earnings - $tax;
                        echo number_format($net_earnings, 2);
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
        
        
    
    <?php

        // get sum
        
            // $total_profit = ($revenue + $other_revenue) - ($total_cost + $expense + $charges + $finance_cost + $loss);
            /* $total_profit = ($revenue + $other_revenue) - ($cost + $logistic + $total_expense  + $finance_cost + $loss + $depreciation); */
            $total_profit = $net_earnings;
            // $total_profit = $revenue - $expense;
            echo "<p class='total_amount' style='background:red; color:#fff; text-decoration:none; padding:10px; width:auto; float:right'>Net Profit: ₦".number_format($total_profit, 2)."</p>";
        
    ?>

</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>