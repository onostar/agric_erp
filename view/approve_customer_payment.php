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
    <h2>Approve Customer payments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Approve customer pyaments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Customer</td>
                <td>Field</td>
                <td>Amount Due</td>
                <td>Amount Paid</td>
                <td>Remark</td>
                <td>Date</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_2cond('payment_evidence', 'payment_status', 'store', 0, $store);
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
                
                <td style="color:red">
                    <?php 
                        //balance
                       $oweds = $get_details->fetch_sum_double('field_payment_schedule', 'amount_due', 'payment_status', 0, 'assigned_id', $detail->assigned_id);
                       if(is_array($oweds)){
                           foreach($oweds as $owed){
                               $balance_due = $owed->total;
                           }
                        }else{
                            $balance_due = 0;
                        }
                        //get total paid amount
                        $paid = $get_details->fetch_sum_double('field_payment_schedule', 'amount_paid', 'payment_status', 0, 'assigned_id', $detail->assigned_id);
                        if(is_array($paid)){
                            foreach($paid as $pay){
                                $amount_paid = $pay->total;
                            }
                        }else{
                            $amount_paid = 0;
                        }
                        $debt = $balance_due - $amount_paid;
                        echo "₦".number_format($debt, 2);
                    ?>
                </td>
                <td style="color:green"><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td><?php echo $detail->remark;?></td>
                
                <td>
                    <?php echo date("d-M-Y, h:ia", strtotime($detail->upload_date))?>
                </td>
                <td>
                    <a href="../receipts/<?php echo $detail->evidence?>" target=" _blank"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="upload payment receipt">View <i class="fas fa-eye"></i></a>
                    
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