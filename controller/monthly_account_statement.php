<?php
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_GET['customer'])){
        $customer = $_GET['customer'];
        $_SESSION['customer'] = $customer;
    }
    $month = $_GET['month'];
    $year = $_GET['year'];
    $full_date = $year."-".$month."-01";
    $prev_month = intval($month) - 1;
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get customer details
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_cond('ledgers', 'acn', $customer);
    foreach($rows as $row){
        $name = $row->ledger;
        $acn = $row->acn;
       
    }
    
?>
<!-- customer info -->
<div class="close_btn">
    <input type="hidden" name="fin_month" value="fin_month" value="<?php echo $month?>">
    <input type="hidden" name="fin_year" value="fin_year" value="<?php echo $year?>">
    <a href="javascript:void(0)" title="Close form" onclick="getMonthlyFinPos()" class="close_form">Close <i class="fas fa-close"></i></a>
</div>
<div class="customer_info" class="allResults">
    <h3 style="background:var(--tertiaryColor)"><?php echo date("F, Y", strtotime($full_date))?> Account Statement for <?php echo $name?></h3>
    <div class="demography">
        <!-- <div class="demo_block">
            <h4><i class="fas fa-id-card"></i> Name:</h4>
            <p><?php echo $name?></p>
        </div> -->
        <div class="demo_block">
            <h4><i class="fas fa-map"></i> Account No.:</h4>
            <p><?php echo $acn?></p>
        </div>
        <div class="demo_block">
            <h4><i class="fas fa-hand-holding-dollar"></i> Opening Balance:</h4>
            <?php
                //get opening balance
                //get previous debit
                $get_prev_debit = new selects();
                $prevs = $get_prev_debit->fetch_sum_monthYearCond('transactions', 'debit', 'post_date', $prev_month, $year, 'account', $customer);
                if(gettype($prevs) == 'array'){
                    foreach($prevs as $prev){
                        $prev_debits = $prev->total;
                    }
                }
                if(gettype($prevs) == 'string'){
                    $opening_bal = 0;
                }
                //get previous credit
                $get_prev_credit = new selects();
                $prevsss = $get_prev_credit->fetch_sum_monthYearCond('transactions', 'credit', 'post_date', $prev_month, $year, 'account', $customer);
                if(gettype($prevsss) == 'array'){
                    foreach($prevsss as $prevss){
                        $prev_credits = $prevss->total;
                    }
                }
                if(gettype($prevsss) == 'string'){
                    $opening_bal = 0;
                }
                $opening_bal = $prev_debits - $prev_credits;
            ?>
            <p><?php echo number_format($opening_bal, 2)?></p>
        </div>
        <!-- <div class="demo_block">
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
        <?php if($wallet >= 0){?>
        <div class="demo_block" style="color:green">
            <h4 style="color:green"><i class="fas fa-piggy-bank"></i> Account balance:</h4>
            <p><?php echo "₦".number_format($wallet, 2)?></p>
        </div>
        <?php }else{?>
        <div class="demo_block" style="color:red">
        <h4><i class="fas fa-piggy-bank"></i> Account balance:</h4>
        <p><?php echo "₦".number_format($wallet, 2)?></p>
        </div>
        <?php }?> -->
    </div>
    <h3 style="background:red; text-align:center; color:#fff; padding:10px;margin:0;">Transactions</h3>
    <div class="transactions">
        <div class="all_credit allResults" style="width:100%">
            <table id="data_table" class="searchTable">
                <thead>
                <tr style="background:var(--primaryColor)">
                        <td>S/N</td>
                        <td>Date</td>
                        <td>Contra Account</td>
                        <td>Details</td>
                        <td>Debit</td>
                        <td>Credit</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //get transaction history
                        $get_transactions = new selects();
                        $details = $get_transactions->fetch_monthlyStatement($customer, $month, $year);
                        $n = 1;
                        if(gettype($details) === 'array'){
                        foreach($details as $detail){

                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                        
                        <td>
                            <?php
                                //get account
                                $get_account = new selects();
                                $acc = $get_account->fetch_details_negCond('transactions', 'account', $detail->account, 'trx_number', $detail->trx_number);
                                if(gettype($acc) == 'array'){
                                    foreach($acc as $ac){
                                        $ledger_no = $ac->account;
                                    }
                                    $get_ledger = new selects();
                                    $ledg = $get_ledger->fetch_details_group('ledgers', 'ledger', 'acn', $ledger_no);
                                    echo $ledg->ledger;
                                }
                               
                            ?>
                        </td> 
                        <td><?php echo $detail->details?></td>  
                        <td style="color:green">
                           <?php
                                echo number_format($detail->debit, 2);
                           ?>
                        </td>
                        <td>
                           <?php
                                echo number_format($detail->credit, 2);
                           ?>
                        </td>
                        
                    </tr>
                    <?php $n++; }}
                        if(gettype($details) == 'array'){
                    ?>
                    <tr>
                        <td style="text-decoration:underline; text-transform:uppercase; color:#222;font-weight:bold;text-align:right"colspan="4">Total</td>
                        
                        <td style="text-decoration:underline; font-weight:bold">
                            <?php
                                //get total debit
                                $get_totals = new selects();
                                $totals = $get_totals->fetch_sum_monthYearCond('transactions', 'debit', 'post_date', $month, $year, 'account', $customer);
                                if(gettype($totals) == 'array'){
                                    foreach($totals as $tot){
                                        echo number_format($tot->total, 2);
                                    }
                                    $net_debit = $tot->total;

                                }
                            ?>
                        </td>
                        <td style="text-decoration:underline; font-weight:bold">
                            <?php
                                //get total credit
                                $get_totals = new selects();
                                $totals = $get_totals->fetch_sum_monthYearCond('transactions', 'credit', 'post_date', $month, $year, 'account', $customer);
                                if(gettype($totals) == 'array'){
                                    foreach($totals as $tot){
                                        echo number_format($tot->total, 2);
                                    }
                                    $net_credit = $tot->total;
                                }
                                
                            ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-decoration:underline; text-transform:uppercase; color:red;font-weight:bold; text-align:right"colspan="4">Net Monthly Balance</td>
                        <td style="text-decoration:underline; font-weight:bold; text-align:left;color:red" colspan="2">
                            <?php
                                $net_balance = $net_debit - $net_credit;
                                echo number_format($net_balance, 2);
                            ?>
                        </td>
                    </tr>
                    <?php }?>
                </tbody>
            </table>
            <?php
                if(gettype($details) == "string"){
                    echo "<p class='no_result'>'$details'</p>";
                }
                
            ?>
        </div>
       
    </div>
    <div id="customer_invoices">
        
    </div>
</div>