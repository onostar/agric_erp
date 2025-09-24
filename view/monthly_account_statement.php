<?php
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_GET['customer'])){
        $customer = $_GET['customer'];
        $_SESSION['customer'] = $customer;
    }
    $month = $_GET['month'];
    $year = $_GET['year'];
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
    <a href="javascript:void(0)" title="Close form" onclick="showPage('../controller/monthly_fin_position.php?month=<?php echo $month?>&year=<?php echo $year?>');" class="close_form">Close <i class="fas fa-close"></i></a>
</div>
<div class="customer_info" class="allResults">
    <h3 style="background:var(--tertiaryColor)">Statement for <?php echo $name?> from '<?php echo date("jS M, Y", strtotime($from)) . "' to '" . date("jS M, Y", strtotime($to))?>'</h3>
    <div class="demography">
        <div class="demo_block">
            <h4><i class="fas fa-id-card"></i> Name:</h4>
            <p><?php echo $name?></p>
        </div>
        <div class="demo_block">
            <h4><i class="fas fa-map"></i> Account No.:</h4>
            <p><?php echo $acn?></p>
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
                        <td>Account</td>
                        <td>Details</td>
                        <td>Debit</td>
                        <td>Credit</td>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        //get transaction history
                        $get_transactions = new selects();
                        $details = $get_transactions->fetch_details_dateGro1con('transactions', 'date(post_date)', $from, $to, 'account', $customer, 'trx_number');
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
                                foreach($acc as $ac){
                                    $ledger_no = $ac->account;
                                }
                                $get_ledger = new selects();
                                $ledg = $get_ledger->fetch_details_group('ledgers', 'ledger', 'acn', $ledger_no);
                                echo $ledg->ledger
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
                    <?php $n++; }}?>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-decoration:underline; font-weight:bold">
                            <?php
                                //get total debit
                                $get_totals = new selects();
                                $totals = $get_totals->fetch_sum_2dateCond('transactions', 'debit', 'account', 'date(post_date)', $from, $to, $detail->account);
                                foreach($totals as $tot){
                                    echo number_format($tot->total, 2);
                                }
                            ?>
                        </td>
                        <td style="text-decoration:underline; font-weight:bold">
                            <?php
                                //get total credit
                                $get_totals = new selects();
                                $totals = $get_totals->fetch_sum_2dateCond('transactions', 'credit', 'account', 'date(post_date)', $from, $to, $detail->account);
                                foreach($totals as $tot){
                                    echo number_format($tot->total, 2);
                                }
                            ?>
                        </td>
                    </tr>
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