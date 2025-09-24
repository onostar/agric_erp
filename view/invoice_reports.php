<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="revenueReport" class="displays management">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_invoices.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Viewing invoices posted today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Daily Invoicing report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice. No.</td>
                <td>Client</td>
                <td>Amount</td>
                <td>Status</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_curdateGro('invoices', 'date(post_date)', 'invoice');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
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
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
        <!-- <div class="all_amounts"> -->
            
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>