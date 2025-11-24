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
    <h2>Pending Field Documentation payments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Documentation pyaments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Client</td>
                <td>Field</td>
                <td>Purchase Cost</td>
                <td>Documentation Fee</td>
                <td>Amount Paid</td>
                <td>Amount Due</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_2cond('assigned_fields', 'contract_status', 'documentation_status', 2, 0);
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
                <td>
                    <?php
                        //get field name
                        $fields = $get_details->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $fields->field_name
                    ?>
                </td>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->total_due, 2);?></td>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->documentation, 2);?></td>
                <td style="color:green">
                    <?php
                         //total paid
                       $paids = $get_details->fetch_sum_single('documentation_fees', 'amount', 'assigned_id', $detail->assigned_id);
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
                       
                        $debt = $detail->documentation - $total_paid;
                        echo "₦".number_format($debt, 2);
                    ?>
                </td>
                
                <td>
                    <a href="javascript:void(0)"  onclick="showPage('post_documentation.php?assigned_id=<?php echo $detail->assigned_id?>&customer=<?php echo $detail->customer?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="Post payment">Post <i class="fas fa-hand-holding-dollar"></i></a>
                    
                    
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