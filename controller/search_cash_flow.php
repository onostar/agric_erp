<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

   
?>

    <div class="search" style="display:flex;align-items:flex-end">
        <h2>Cash Flow statement from '<?php echo date("jS M, Y", strtotime($from)) . "' to '" . date("jS M, Y", strtotime($to))?>'</h2>
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Cash Flow Statement from <?php echo date('Y-m-d', strtotime($from))?> to <?php echo date('Y-m-d', strtotime($to))?>' )"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <hr>
    
    <div class="statements">
    <div class="sub_statements">
        
        <table id="data_table" class="searchTable">
            
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
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'trans_type',
                            $from, $to, 'operating',  'inflow');
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
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'operating',  'inventory purchase');
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
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'operating',  'expense');
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
                            //get all cash flow from expense
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'operating',  'bank charges');
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
                            //get all cash flow from expense
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'operating',  'tax payments');
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
                    <td style="text-decoration:underline;font-weight:bold;color:var(--secondaryColor)">
                        <?php
                            //fetch total for inflow 
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'trans_type',
                            $from, $to, 'operating',  'inflow');
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
                            $rowsss = $get_outflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'trans_type',
                            $from, $to, 'operating',  'outflow');
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
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'investing',  'asset sales');
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
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'investing',  'asset purchase');
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
                    <td style="text-decoration:underline;font-weight:bold; color:#222; text-transform:uppercase">Net Cash Flow From Investments</td>
                    <td style="text-decoration:underline;font-weight:bold; color:var(--secondaryColor)">
                        <?php
                            //fetch total for inflow 
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'trans_type',
                            $from, $to, 'investing',  'inflow');
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
                            $rowsss = $get_outflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'trans_type',
                            $from, $to, 'investing',  'outflow');
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
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'financing',  'Loan Received');
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
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'financing',  'Director Contribution');
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
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'financing',  'Loan Payment');
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
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'financing',  'Director Remuneration');
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
                            //get all cash flow from customers
                            $get_inflow = new selects();
                            $rows = $get_inflow->fetch_sum_2date2Cond('cash_flows', 'amount', 'date(post_date)', 'activity', 'details',
                            $from, $to, 'financing',  'finance cost');
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
        $tinfs = $get_total_inflow->fetch_sum_2dateCond('cash_flows', 'amount', 'trans_type', 'date(post_date)', $from, $to, 'inflow');
        if(gettype($tinfs) == 'array'){
            foreach($tinfs as $tinf){
                $total_inflows = $tinf->total;
            }
        }
        //get total outflow
        $get_total_outflow = new selects();
        $touts = $get_total_outflow->fetch_sum_2dateCond('cash_flows', 'amount', 'trans_type', 'date(post_date)', $from, $to, 'outflow');
        if(gettype($tinfs) == 'array'){
            foreach($touts as $tout){
                $total_outflows = $tout->total;
            }
        }
        $net_cash = $total_inflows - $total_outflows;
        if($net_cash < 0){
            echo "<p class='total_amount' style='color:#222; text-decoration:none'>Net Cash flow ast at end of ".date("jS M, Y", strtotime($to)).": <span style='text-decoration:underline;color:red'>₦".number_format($net_cash, 2)."</span></p>";
        }else{
            echo "<p class='total_amount' style='color:#222; text-decoration:none'>Net Cash flow ast at end of ".date("jS M, Y", strtotime($to)).": <span style='text-decoration:underline;color:green'>₦".number_format($net_cash, 2)."</span></p>";
        }
        
    ?>