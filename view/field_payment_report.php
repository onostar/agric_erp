<?php
    session_start();
    // $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
    

?>
<style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="revenueReport" class="displays management" style="margin:0!important;width:100%!important">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_field_payment_report.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Todays Field Payments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Todays Field Payments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Client</td>
                <td>Field</td>
                <td>Receipt No.</td>
                <td>Amount Paid</td>
                <td>Payment Mode</td>
                <td>Trx Date</td>
                <td>Time</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_curdateCon('field_payments', 'post_date', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get client name
                        $client = $get_details->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
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
                <td><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td><?php echo $detail->payment_mode;?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->trx_date))?></td>
                <td>
                    <?php echo date("h:i:sa", strtotime($detail->post_date))?>
                </td>
                <td>
                    <a href="javascript:void(0)"  onclick="printPaymentReceipt('<?php echo $detail->invoice;?>')"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="print receipt">Print <i class="fas fa-print"></i></a>
                    
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        //get total cos of payments today
        $ttls = $get_details->fetch_sum_curdateCon('field_payments', 'amount', 'date(post_date)', 'store', $store);
        if(gettype($ttls) === 'array'){
            foreach($ttls as $ttl){
                echo "<p class='total_amount' style='color:green; text-align:center;'>Total: ₦".number_format($ttl->total, 2)."</p>";
            }
        }
    ?>
       
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>
<?php }else{
        echo "<p class='no_result'>Session expired. Please login again</p>";
    }
    ?>