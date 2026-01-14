<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2date2ConGr('field_payments', 'date(post_date)', $from, $to, 'store', $store, 'invoice');
    $n = 1;
?>
<h2>Field Payments between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Field Payments report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
		        <td>S/N</td>
                <td>Customer</td>
                <td>Field</td>
                <td>Receipt No.</td>
                <td>Amount Paid</td>
                <td>Payment Mode</td>
                <td>Trx Date</td>
                <td>Date</td>
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
                  <td>
                    <?php
                        //get client name
                        $client = $get_revenue->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
                        echo $client->customer;
                    ?>  
                </td>
                <td>
                    <?php
                        //get field name
                        //get field from assigned_id first
                        $get_field = new selects();
                        $fds = $get_field->fetch_details_group('assigned_fields', 'field', 'assigned_id', $detail->loan);
                        $fields = $get_field->fetch_details_group('fields', 'field_name', 'field_id', $fds->field);
                        echo $fields->field_name
                    ?>
                </td>
                <td style="color:var(--primaryColor)"><?php echo $detail->invoice;?></td>
               <td>
                    <?php
                        //total paid
                        $ttl = $get_revenue->fetch_sum_single('field_payments', 'amount', 'invoice', $detail->invoice);
                        foreach($ttl as $tt){
                            echo "₦".number_format($tt->total, 2);
                        }
                    ?>
                </td>
                <td><?php echo $detail->payment_mode;?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->trx_date))?></td>
                <td>
                    <?php echo date("d-M-Y,h:ia", strtotime($detail->post_date))?>
                </td>
                <td>
                    <a href="javascript:void(0)"  onclick="printPaymentReceipt('<?php echo $detail->invoice;?>')"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="print receipt">Print <i class="fas fa-print"></i></a>
                    
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
    //get total cos of payments today
    $ttls = $get_revenue->fetch_sum_2dateCond('field_payments', 'amount', 'store', 'date(post_date)', $from, $to,  $store);
    if(gettype($ttls) === 'array'){
        foreach($ttls as $ttl){
            echo "<p class='total_amount' style='color:green; text-align:center;'>Total: ₦".number_format($ttl->total, 2)."</p>";
        }
    }
?>
