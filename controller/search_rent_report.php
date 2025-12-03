<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateConOrder('rent_payments', 'store', 'date(post_date)', $from, $to, $store, 'post_date');
    $n = 1;
?>
<h2>Rent Return Payments between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Concentrate Investment Returns report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
		        <td>S/N</td>
                <td>Customer</td>
                <td>Investment No.</td>
                <td>Receipt No.</td>
                <td>Amount Paid</td>
                <td>Mode</td>
                <td>Trx Date</td>
                <td>Date</td>
                <td>Posted By</td>
                
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
                        //get field details
                        //first get the assigned field from assigned table
                        $ass = $get_revenue->fetch_details_group('assigned_fields', 'field', 'assigned_id', $detail->loan);
                        $field = $get_revenue->fetch_details_group('fields', 'field_name', 'field_id', $ass->field);
                        echo $field->field_name;
                    ?>
                </td>

                <td style="color:var(--primaryColor)"><?php echo $detail->invoice;?></td>
                <td><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td><?php echo $detail->payment_mode;?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->trx_date))?></td>
                <td>
                    <?php echo date("d-M-Y, h:ia", strtotime($detail->post_date))?>
                </td>
                <td>
                    <?php
                        //get posted by
                        $pstd = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $pstd->full_name;
                    ?>
                    
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
    $ttls = $get_revenue->fetch_sum_2dateCond('rent_payments', 'amount', 'store', 'date(post_date)', $from, $to,  $store);
    if(gettype($ttls) === 'array'){
        foreach($ttls as $ttl){
            echo "<p class='total_amount' style='color:green; text-align:center;'>Total: ₦".number_format($ttl->total, 2)."</p>";
        }
    }
?>
