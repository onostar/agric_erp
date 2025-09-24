<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_dateGro('invoices', 'date(post_date)', $from, $to, 'invoice');
    $n = 1;
?>
<h2>Viewing Invoices posted between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Invoicing report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice. No.</td>
                <td>Client</td>
                <td>Amount</td>
                <td>Status</td>
                <td>Date</td>
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
                <td><?php echo $detail->invoice?></td>
                <td>
                    <?php 
                        //get customer
                        $get_customer = new selects();
                        $custs = $get_customer->fetch_details_cond('customers', 'customer_id', $detail->customer);
                        foreach($custs as $cust){
                            echo $cust->customer;
                        }
                    ?>
                </td>
                <td style="color:green">
                    <?php
                        $get_amount = new selects();
                        $rows = $get_amount->fetch_sum_single('invoices', 'amount', 'invoice', $detail->invoice);
                        foreach($rows as $row){
                            $total_amount = $row->total;
                        }
                        echo "₦".number_format($total_amount, 2);
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->invoice_status == 1){
                            echo "<p style='color:red'>Not paid</p>";
                        }else{
                            echo "<p style='color:green'>Paid</p>";

                        }
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("h:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0);" title="View details" style="padding:5px; background:var(--otherColor);color:#fff; border-radius:15px;" onclick="showPage('client_invoice_details.php?payment_id=<?php echo $detail->invoice?>')">View <i class="fas fa-eye"></i></a>
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    
    if(gettype($details) == 'string'){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
