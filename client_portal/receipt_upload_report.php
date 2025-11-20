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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_receipts_uploads.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Payment Receipts Uploaded Today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Payment Receipts Uploaded Today')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Amount Paid</td>
                <td>Remark</td>
                <td>Status</td>
                <td>Time</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_curdateCon('payment_evidence', 'upload_date', 'customer', $customer);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get field name
                        $get_field = new selects();
                        $fields = $get_field->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $fields->field_name
                    ?>
                </td>
                
                <td><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td><?php echo $detail->remark;?></td>
                <td>
                    <?php
                        if($detail->payment_status == 0){
                            echo "<span style='color:var(--primaryColor);'>Pending <i class='fas fa-spinner'></i></span>";
                        }elseif($detail->payment_status == 1){
                            echo "<span style='color:green;'>Approved <i class='fas fa-check'></i></span>";
                        }else{
                            echo "<span style='color:red;'>Rejected <i class='fas fa-times'></i></span>";
                        }
                    ?>
                </td>
                <td>
                    <?php echo date("h:i:sa", strtotime($detail->upload_date))?>
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