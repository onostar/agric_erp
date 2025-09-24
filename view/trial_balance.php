<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management" style="width:100%!important">
    <div class="select_date">
        <!-- <form method="POST"> -->
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_trial_balance.php')">Search <i class="fas fa-search"></i></button>
</section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Trial Balance as at today <?php echo date("Y-M-d")?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Trial Balance')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Account Name</td>
                <td>Account No.</td>
                <td>Debit</td>
                <td>Credit</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_trial_balance();
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        //get ledger
                        $get_ledger = new selects();
                        $ledg = $get_ledger->fetch_details_group('ledgers', 'ledger', 'acn', $detail->account);
                        echo $ledg->ledger;
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php echo $detail->account?></td>
                
                <td style="color:green">
                    <?php echo "₦".number_format($detail->debits, 2);?>
                </td>
                <td style="color:red">
                    <?php echo "-₦".number_format($detail->credits, 2);?>
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
<div class="debit_credit" style="display:flex;justify-content:center; gap:1rem">
    <?php
        // get total debits
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_curdateCon('transactions', 'debit', 'post_date', 'trx_status', 0);
        foreach($amounts as $amount){
            $paid_amount = $amount->total;
        }
        
            echo "<p class='total_amount' style='color:green'>Total Debits: ₦".number_format($paid_amount, 2)."</p>";
        // get total credits
        $get_credit = new selects();
        $amounts = $get_credit->fetch_sum_curdateCon('transactions', 'credit', 'post_date', 'trx_status', 0);
        foreach($amounts as $amount){
            $credit_amount = $amount->total;
        }
        
            echo "<p class='total_amount' style='color:red'>Total Credit: -₦".number_format($credit_amount, 2)."</p>";
            
        
    ?>
</div>
</div>
