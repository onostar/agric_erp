<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="debt_paymentReport" class="displays management" style="width:100%!important">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_vendor_payments.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Todays vendor payments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Vendor</td>
                <td>Amount</td>
                <td>Trans. Date</td>
                <td>Post Time</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon1Neg('purchase_payments', 'date(post_date)', 'store', $store, 'payment_mode', 'Credit');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get customer
                        $client = $get_users->fetch_details_group('vendors', 'vendor', 'vendor_id', $detail->vendor);
                        echo $client->vendor;
                    ?>
                </td>
                <!-- <td><a style="color:green" href="javascript:void(0)" title="View invoice details"><?php echo $detail->invoice?></a></td>-->
                <td>
                    <?php echo "₦".number_format($detail->amount_paid, 2);?>
                </td>
                <!-- <td>
                    <?php echo $detail->payment_mode?>
                </td> -->
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--otherColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    <div class="all_modes">
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        
        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_curdate2Con1neg('purchase_payments', 'amount_paid', 'post_date', 'store', $store, 'payment_mode', 'Credit');
        if(gettype($amounts) == 'array'){
            foreach($amounts as $amount){
                echo "<p class='total_amount' style='color:green'>Total: ₦".number_format($amount->total, 2)."</p>";
            }
        }
    ?>
    </div>
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>