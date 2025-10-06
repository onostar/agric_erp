<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateCon('labour_payments', 'store', 'date(post_date)', $from, $to,  $store);
    $n = 1;
?>
<h2>Labour Cost Payments between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Labour Cost Transactions')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Trx. Date</td>
                <td>Task</td>
                <td>Expense Head</td>
                <td>Contra Ledger</td>
                <td>Trx. No.</td>
                <td>Amount</td>
                <td>Post Date</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:green">
                    <?php echo date("d-M-Y", strtotime($detail->trans_date))?>
                </td>
                <td>
                    <?php 
                        $row = $get_revenue->fetch_details_group('tasks', 'title', 'task_id', $detail->task);
                        echo $row->title
                    ?>
                </td>
                <td>
                    <?php 
                        $rows = $get_revenue->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->exp_head);
                        echo $rows->ledger
                    ?>
                </td>
                <td>
                    <?php 
                        $rows = $get_revenue->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->contra);
                        echo $rows->ledger
                    ?>
                </td>
                <td><?php echo $detail->trx_number?></td>
                <td style="color:red;"><?php echo "₦".number_format($detail->amount, 2)?></td>
                
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date))?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
    <div class="all_modes">

<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
    
   
?>
    <div class="all_modes">
        <?php
             //get contribution
            $get_cash = new selects();
            $cashs = $get_cash->fetch_sum_2dateCond('labour_payments', 'amount', 'store', 'date(post_date)', $from, $to, $store);
            if(gettype($cashs) === "array"){
                foreach($cashs as $cash){
                ?>
                    <p class="sum_amount" style="background:var(--tertiaryColor)"><strong>Total Cost</strong>: ₦ <?php echo number_format($cash->total, 2)?></p>

                <?php
                }
            }
        ?>
        
    </div>
    </div>