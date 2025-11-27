<?php
    session_start();
    // $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
       $customer = $_SESSION['user_id'];
    

?>
<style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="revenueReport" class="displays management" style="margin:0!important;width:100%!important">
    
<div class="displays allResults new_data" id="revenue_report">
    <h2>My Current Concentrate Investments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Investment pyaments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Investment No.</td>
                <td>Currency</td>
                <td>Amount</td>
                <td>Value in Naira (NGN)</td>
                <td>Amount Paid (NGN)</td>
                <td>Amount Due (NGN)</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condOrder('investments', 'customer', $customer, 'post_date');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
               
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
                    <a href="javascript:void(0)"  onclick="showPage('post_investment.php?investment=<?php echo $detail->investment_id?>&customer=<?php echo $detail->customer?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="Post payment">Post <i class="fas fa-hand-holding-dollar"></i></a>
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
    ?>
       
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>
<?php }else{
        echo "<p class='no_result'>Session expired. Please login again</p>";
    }
    ?>