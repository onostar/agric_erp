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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_deposits.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Todays payments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Customer</td>
                <td>Invoice</td>
                <td>Amount</td>
                <td>Payment Mode</td>
                <td>Trans. Date</td>
                <td>Post Time</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateCon('deposits', 'date(post_date)', 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get customer
                        $get_customer = new selects();
                        $client = $get_customer->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
                        echo $client->customer;
                    ?>
                </td>
                <td><a style="color:green" href="javascript:void(0)" title="View invoice details"><?php echo $detail->invoice?></a></td>
                <td>
                    <?php echo "₦".number_format($detail->amount, 2);?>
                </td>
                <td>
                    <?php echo $detail->payment_mode?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->trans_date));?></td>
                <td style="color:var(--otherColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
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
        //get cash
        $get_cash = new selects();
        $cashs = $get_cash->fetch_sum_curdate2Con('deposits', 'amount', 'post_date', 'payment_mode', 'Cash', 'store', $store);
        if(gettype($cashs) === "array"){
            foreach($cashs as $cash){
            ?>
                <p class="sum_amount" onclick="showPage('cash_list.php')"style="background:var(--otherColor)"><strong>Cash</strong>: ₦ <?php echo number_format($cash->total, 2)?></p>

            <?php
            }
        }
        //get pos
        $get_pos = new selects();
        $poss = $get_pos->fetch_sum_curdate2Con('deposits', 'amount', 'post_date', 'payment_mode', 'POS', 'store', $store);
        if(gettype($poss) === "array"){
            foreach($poss as $pos){
                ?>
                <p class="sum_amount" onclick="showPage('pos_list.php')"style="background:var(--secondaryColor)"><strong>POS</strong>: ₦ <?php echo number_format($pos->total, 2)?></p>

            <?php
            }
        }
        //get transfer
        $get_transfer = new selects();
        $trfs = $get_transfer->fetch_sum_curdate2Con('deposits', 'amount', 'post_date', 'payment_mode', 'Transfer', 'store', $store);
        if(gettype($trfs) === "array"){
            foreach($trfs as $trf){
                ?>
                <p class="sum_amount" onclick="showPage('transfer_list.php')"style="background:var(--primaryColor)"><strong>Transfer</strong>: ₦ <?php echo number_format($trf->total, 2)?></p>

            <?php
            }
        }
        // get sum
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_curdateCon('deposits', 'amount', 'post_date', 'store', $store);
        foreach($amounts as $amount){
            echo "<p class='total_amount' style='color:green'>Total: ₦".number_format($amount->total, 2)."</p>";
        }
    ?>
    </div>
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>