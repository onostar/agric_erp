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
        font-size:.7rem!important;
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_concentrate_investments.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Showing Investments Posted Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Investment pyaments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Time</td>
                <td>Client</td>
                <td>Inv. No.</td>
                <td>Currency</td>
                <td>Amount</td>
                <td>Value in Naira</td>
                <td>Amount Paid</td>
                <td>Amount Due</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condOrder('investments', 'store', $store, 'post_date');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo date("h:i:sa", strtotime($detail->post_date))?></td>
                <td>
                    <?php
                        //get client
                        $cls = $get_details->fetch_details_cond('customers', 'customer_id', $detail->customer);
                        foreach($cls as $cl){
                            $client = $cl->customer;
                        }
                        echo $client;
                    ?>
                </td>
                <td style="color:var(--primaryColor)"><?php echo "DAV/CON/00$detail->investment_id"?></td>
                <td>
                    <?php
                        echo $detail->currency;
                    ?>
                </td>
                <?php
                    if($detail->currency == "Dollar"){
                ?>
                <td style="color:var(--otherColor)"><?php echo "$".number_format($detail->amount, 2);?></td>
                <?php }else{?>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->amount, 2);?></td>
                <?php }?>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->total_in_naira, 2);?></td>
                <td style="color:green">
                    <?php
                         //total paid
                       $paids = $get_details->fetch_sum_single('investment_payments', 'amount_in_naira', 'investment', $detail->investment_id);
                       if(is_array($paids)){
                           foreach($paids as $paid){
                               $total_paid = $paid->total;
                           }
                        }else{
                            $total_paid = 0;
                        }
                        echo "₦".number_format($total_paid, 2);
                    ?>
                </td>
                <td style="color:red">
                    <?php 
                       
                        $debt = $detail->total_in_naira - $total_paid;
                        echo "₦".number_format($debt, 2);
                    ?>
                </td>
                <td>
                    <?php
                        if($detail->contract_status == 0){
                            echo "<span style='color:var(--primaryColor)'>Pending <i class='fas fa-spinner'></i></span>";
                        }elseif($detail->contract_status == 1){
                            echo "<span style='color:var(--otherColor)'>Active <i class='fas fa-chart-line'></i></span>";
                        }else{
                            echo "<span style='color:green'>Completed <i class='fas fa-check'></i></span>";
                        }
                    ?>
                    <?php if($detail->contract_status != 0){?>
                    <a href="javascript:void(0)"  onclick="showPage('view_client_investment.php?investment=<?php echo $detail->investment_id?>&customer=<?php echo $detail->customer?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="View details">View <i class="fas fa-eye"></i></a>
                    <?php }?>
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
        $ttls = $get_details->fetch_sum_curdateCon('investments', 'total_in_naira', 'date(post_date)', 'store', $store);
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