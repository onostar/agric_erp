<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management" style="width:70%!important; margin:0 20px!important">
    <div class="select_date" >
        <!-- <form method="POST"> -->
        <section >    
            <div class="from_to_date" style="width:30%!important">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date" style="width:30%!important">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_cash_flow.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
        
    <div class="search" style="display:flex;align-items:flex-end">
        <h2 style="color:green">Cash Flow statement for today <?php echo date("d-M-Y")?> </h2>
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Cash Flow Statement for <?php echo date('Y-m-d')?>')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <hr>
    
    <div class="statements">
    <div class="sub_statements">
        
        <table id="data_table" class="searchTable">
            <thead>
                
            </thead>
            <tbody>
                <tr style="background:var(--primaryColor)">
                    <td style="color:#fff;text-align:left;text-transform:uppercase;font-weight:bold">Cash flow from operations</td>
                    <td style="color:#fff;text-align:left;text-transform:uppercase;font-weight:bold">Amount(₦)</td>
                </tr>
                <tr>
                    
                    <td colspan="2" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Cash Receipts from</td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">
                        Customers
                    </td>
                    <td>
                        <?php
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'trans_type', 'inflow');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $income = $row->total;
                                }
                                echo number_format($income, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Other Operations</td>
                    <td>0.00</td>
                   
                </tr>
                <tr>
                    <td colspan="2" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Cash Paid for</td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">
                        Purchases
                    </td>
                    <td>
                        <?php 
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'details', 'inventory purchase');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                    
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">
                        General Expense
                    </td>
                    <td>
                        <?php 
                            //get all cash flow from expense
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'details', 'expense');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $expense = $row->total;
                                }
                                echo number_format($expense, 2);
                            }
                           if(gettype($rows) == 'string'){
                            echo "0.00";
                           }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">
                        Bank Charges
                    </td>
                    <td>
                        <?php 
                            //get all cash flow from bank charges
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'details', 'bank charges');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $expense = $row->total;
                                }
                                echo number_format($expense, 2);
                            }
                           if(gettype($rows) == 'string'){
                            echo "0.00";
                           }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">
                        Tax Payments
                    </td>
                    <td>
                        <?php 
                            //get all cash flow from bank charges
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'details', 'tax payments');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $expense = $row->total;
                                }
                                echo number_format($expense, 2);
                            }
                           if(gettype($rows) == 'string'){
                            echo "0.00";
                           }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td style="text-decoration:underline;font-weight:bold; color:#222; text-transform:uppercase">Net Cash Flow From Operations</td>
                    <td style="text-decoration:underline;font-weight:bold; color:var(--secondaryColor)">
                        <?php
                            //fetch total for inflow 
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'trans_type', 'inflow');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $inflows = $row->total;
                                }
                            }
                            if(gettype($rows) == 'string'){
                                $inflows = 0;
                            }
                            //fetch total for inflow 
                            $get_outflow = new selects();
                            $rowsss = $get_outflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'operating', 'trans_type', 'outflow');
                            if(gettype($rowsss) == 'array'){
                                foreach($rowsss as $rowss){
                                    $outflow = $rowss->total;
                                }
                            }
                            if(gettype($rowsss) == 'string'){
                                $outflow = 0;
                            }
                            $net_cashflow = $inflows - $outflow;
                            echo number_format($net_cashflow, 2);
                        ?>
                    </td>
                    
                </tr>
                
                <tr style="background:var(--primaryColor)">
                    <td style="color:#fff;text-align:left;text-transform:uppercase;font-weight:bold">Cash flow from Investing Activities</td>
                    <td style="color:#fff;text-align:left;text-transform:uppercase;font-weight:bold">Amount(₦)</td>
                </tr>
                <tr>
                    
                    <td colspan="2" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Cash Receipts from</td>
                </tr>

                <tr>
                    
                <tr>
                    <td style="color:#222; text-align:left">Sales Of Asset</td>
                    <td>
                        <?php 
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'investing', 'details', 'asset sales');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Sales Of Investments securities</td>
                    <td>0.00</td>
                   
                </tr>
                <tr>
                    <td colspan="2" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Cash Paid for</td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">
                        Puchase of Assets
                    </td>
                    <td>
                        <?php 
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'investing', 'details', 'asset purchase');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                    
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Purchase of Investments securities</td>
                    <td>0.00</td>
                   
                </tr>
                
                <tr>
                    <td style="text-decoration:underline;font-weight:bold; color:#222; text-transform:uppercase; ">Net Cash Flow From Investments</td>
                    <td style="text-decoration:underline;font-weight:bold; color:var(--secondaryColor)">
                        <?php
                            //fetch total for inflow 
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'investing', 'trans_type', 'inflow');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $inflows = $row->total;
                                }
                            }
                            if(gettype($rows) == 'string'){
                                $inflows = 0;
                            }
                            //fetch total for inflow 
                            $get_outflow = new selects();
                            $rowsss = $get_outflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'investing', 'trans_type', 'outflow');
                            if(gettype($rowsss) == 'array'){
                                foreach($rowsss as $rowss){
                                    $outflow = $rowss->total;
                                }
                            }
                            if(gettype($rowsss) == 'string'){
                                $outflow = 0;
                            }
                            $net_cashflow = $inflows - $outflow;
                            echo number_format($net_cashflow, 2);
                        ?>
                    </td>
                    
                </tr>
                
                <tr style="background:var(--primaryColor)!important">
                    <td style="color:#fff;text-align:left;text-transform:uppercase;font-weight:bold">Cash flow from Financing Activities</td>
                    <td style="color:#fff;text-align:left;text-transform:uppercase;font-weight:bold">Amount(₦)</td>
                </tr>
                <tr>
                    
                    <td colspan="2" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Cash Receipts from</td>
                </tr>

                <tr>
                    
                <tr>
                    <td style="color:#222; text-align:left">Issuance of stock</td>
                    <td>0.00</td>
                   
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Loans/Mortgage From Bank</td>
                    <td>
                        <?php 
                            //get all cash flow from loans
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'details', 'Loan Received');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Director's Capital Contribution</td>
                    <td>
                        <?php 
                            //get all cash flow from loans
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'details', 'Director Contribution');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td colspan="2" style="color:var(--moreColor);text-align:left;text-transform:uppercase; font-weight:bold">Cash Paid for</td>
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Re-Purchase of Stocks</td>
                    <td>0.00</td>
                    
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Payment of loans</td>
                    <td>
                        <?php 
                            //get all cash flow from loans
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'details', 'Loan Payment');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Director's Remuneration</td>
                    <td>
                        <?php 
                            //get all cash flow from loans
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'details', 'Director Remuneration');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td style="color:#222; text-align:left">Other Finance Cost (Loan fees, Interest, etc)</td>
                    <td>
                        <?php 
                            //get all cash flow from loans
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'details', 'finance cost');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $purchase = $row->total;
                                }
                                echo number_format($purchase, 2);
                            }
                            if(gettype($rows) == 'string'){
                                echo "0.00";
                            }
                            
                        ?>
                    </td>
                   
                </tr>
                <tr>
                    <td style="text-decoration:underline;font-weight:bold; color:#222; text-transform:uppercase">Net Cash Flow From Financing</td>
                    <td style="text-decoration:underline;font-weight:bold; color:var(--secondaryColor)">
                        <?php
                            //fetch total for inflow 
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'trans_type', 'inflow');
                            if(gettype($rows) == 'array'){
                                foreach($rows as $row){
                                    $inflows = $row->total;
                                }
                            }
                            if(gettype($rows) == 'string'){
                                $inflows = 0;
                            }
                            //fetch total for inflow 
                            $get_outflow = new selects();
                            $rowsss = $get_outflow->fetch_sum_curdate2Con('cash_flows', 'amount', 'date(post_date)', 'activity', 'financing', 'trans_type', 'outflow');
                            if(gettype($rowsss) == 'array'){
                                foreach($rowsss as $rowss){
                                    $outflow = $rowss->total;
                                }
                            }
                            if(gettype($rowsss) == 'string'){
                                $outflow = 0;
                            }
                            $net_cashflow = $inflows - $outflow;
                            echo number_format($net_cashflow, 2);
                            echo "0.00";
                        ?>
                    </td>
                    
                </tr>
                
            </tbody>
        </table>
    </div>
</div>
    
    <?php
        //get total  inflow
        $get_total_inflow = new selects();
        $tinfs = $get_total_inflow->fetch_sum_curdateCon('cash_flows', 'amount', 'date(post_date)', 'trans_type', 'inflow');
        if(gettype($tinfs) == 'array'){
            foreach($tinfs as $tinf){
                $total_inflows = $tinf->total;
            }
        }
        //get total outflow
        $get_total_outflow = new selects();
        $touts = $get_total_outflow->fetch_sum_curdateCon('cash_flows', 'amount', 'date(post_date)', 'trans_type', 'outflow');
        if(gettype($tinfs) == 'array'){
            foreach($touts as $tout){
                $total_outflows = $tout->total;
            }
        }
        $net_cash = $total_inflows - $total_outflows;
        if($net_cash < 0){
            echo "<p class='total_amount' style='color:red'>Net Cash flow for today: ₦".number_format($net_cash, 2)."</p>";
        }else{
            echo "<p class='total_amount' style='color:green'>Net Cash flow for today: ₦".number_format($net_cash, 2)."</p>";

        }
    ?>
       
            
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>