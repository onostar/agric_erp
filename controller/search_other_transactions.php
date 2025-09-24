<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateCon('other_transactions', 'store', 'date(post_date)', $from, $to,  $store);
    $n = 1;
?>
<h2>Other Transactions between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Other Transactions')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Transaction Date</td>
                <td>Ledger</td>
                <td>Contra Ledger</td>
                <td>Type</td>
                <td>Amount</td>
                <td>Post Date</td>
                <td>Post Time</td>
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
                        $get_loaner = new selects();
                        $row = $get_loaner->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->ledger);
                        echo $row->ledger
                    ?>
                </td>
                <td>
                    <?php 
                        $get_loanee = new selects();
                        $rows = $get_loanee->fetch_details_group('ledgers', 'ledger', 'ledger_id', $detail->contra_ledger);
                        echo $rows->ledger
                    ?>
                </td>
                <td><?php echo $detail->trans_type?></td>
                <td style="color:red;"><?php echo "₦".number_format($detail->amount, 2)?></td>
                
                <td style="color:var(--otherColor)"><?php echo date("d-M-Y", strtotime($detail->post_date))?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date))?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
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
    <div class="all_modes" style="display:none">
        <?php
             //get contribution
            $get_cash = new selects();
            $cashs = $get_cash->fetch_sum_2dateCond('other_transactions', 'amount', 'store', 'date(post_date)', $from, $to, $store);
            if(gettype($cashs) === "array"){
                foreach($cashs as $cash){
                ?>
                    <p class="sum_amount" style="background:var(--otherColor)"><strong>Total</strong>: ₦ <?php echo number_format($cash->total, 2)?></p>

                <?php
                }
            }
        ?>
        
        
    </div>
    </div>