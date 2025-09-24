<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_dateGroOrder('transactions', 'date(post_date)', $from, $to, 'trx_number', 'post_date');
    $n = 1;
?>
<h2>Transactions posted between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Main Ledger</td>
                <td>Contra Ledger</td>
                <td>Amount</td>
                <td>Details</td>
                <td>Trans. Date</td>
                <td>Post Date</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){

?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)">
                    <?php
                        $get_head = new selects();
                        $heads = $get_head->fetch_details_group('ledgers', 'ledger', 'acn', $detail->account);
                        echo $heads->ledger;
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php
                        $get_head = new selects();
                        $heads = $get_head->fetch_details_negCond('transactions', 'account', $detail->account,'trx_number',  $detail->trx_number);
                        if(gettype($heads) == 'array'){
                            foreach($heads as $head){
                                $contra = $head->account;
                            }
                            $get_contra = new selects();
                            $conts = $get_contra->fetch_details_group('ledgers', 'ledger', 'acn', $contra);
                            echo $conts->ledger;
                        }
                        
                    ?>
                </td>
                <td style="color:red">
                    <?php
                        if($detail->debit == 0){
                            echo "₦".number_format($detail->credit, 2);
                        }else{
                            echo "₦".number_format($detail->debit, 2);

                        }
                    ?>
                </td>
                <td><?php echo $detail->details?></td>
                <td style="color:var(--otherColor)"><?php echo date("jS M, Y", strtotime($detail->trans_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    
                    <a style="color:#fff;background:var(--secondaryColor); padding:5px; border-radius:5px" href="javaScript:void(0)" title="Reverse transaction" onclick="reverseTrx('<?php echo $detail->trx_number?>')"><i class="fas fa-arrow-left-rotate"></i></a>
                    
                    
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
    </div>