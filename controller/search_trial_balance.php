<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    // $details = $get_revenue->fetch_trial_balance($from, $to);
    $n = 1;
?>
<h2>Trial balance between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
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
                $details = $get_users->fetch_trial_balanceDate($from, $to);
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
    $amounts = $get_total->fetch_sum_2dateCond('transactions', 'debit', 'trx_status', 'date(post_date)', $from, $to, 0);
    foreach($amounts as $amount){
        $paid_amount = $amount->total;

    }
    
        echo "<p class='total_amount' style='color:green'>Total Debit: ₦".number_format($paid_amount, 2)."</p>";
        
    // get total credits
    $get_credit = new selects();
    $amounts = $get_credit->fetch_sum_2dateCond('transactions', 'credit', 'trx_status', 'date(post_date)', $from, $to, 0);
    foreach($amounts as $amount){
        $paid_amount = $amount->total;

    }
    
        echo "<p class='total_amount' style='color:red'>Total Credit: -₦".number_format($paid_amount, 2)."</p>";
        
    
?>
    </div>