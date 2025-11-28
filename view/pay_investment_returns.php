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
    
<div class="displays allResults new_data" id="revenue_report">
    <h2>Active Concentrate Investments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Active Investments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Client</td>
                <td>Investment No.</td>
                <td>Currency</td>
                <td>Amount</td>
                <td>Value in Naira (NGN)</td>
                <td>Total Returns (NGN)</td>
                <td>Total Paid (NGN)</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condOrder('investments', 'contract_status', 1, 'post_date');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get customer name
                        $customers = $get_details->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
                        echo $customers->customer
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
                        //total retunrs
                       $debts = $get_details->fetch_sum_single('investment_returns', 'amount_due', 'investment_id', $detail->investment_id);
                       if(is_array($debts)){
                           foreach($debts as $debt){
                               $total_returns = $debt->total;
                           }
                        }else{
                            $total_returns = 0;
                        }
                        echo "₦".number_format($total_returns, 2);
                    ?>
                </td>
                <td style="color:red">
                    <?php 
                       //total returns paid
                        $paids = $get_details->fetch_sum_single('investment_returns', 'amount_paid', 'investment_id', $detail->investment_id);
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
                
                <td>
                    <a href="javascript:void(0)"  onclick="showPage('pay_returns.php?investment=<?php echo $detail->investment_id?>&customer=<?php echo $detail->customer?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="Post payment">View <i class="fas fa-eye"></i></a>
                    
                    
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