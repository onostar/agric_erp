<?php
    session_start();
    // $store = $_SESSION['store_id'];
    $customer = $_SESSION['user_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_2dateConOrder('payment_evidence', 'customer', 'date(upload_date)', $from, $to, $customer, 'upload_date');
    $n = 1;
?>
<h2>Payment Receipts Uploaded between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Payment Receipts Uploaded report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
		        <td>S/N</td>
                <td>Field</td>
                <td>Amount Paid</td>
                <td>Remark</td>
                <td>Status</td>
                <td>Date</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
<?php
    if(gettype($details) === 'array'){
    foreach($details as $detail){
       
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
                    <?php echo date("d-M-Y, h:ia", strtotime($detail->upload_date))?>
                </td>
                <td>
                    <a href="../receipts/<?php echo $detail->evidence?>" target=" _blank"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="upload payment receipt">View <i class="fas fa-eye"></i></a>
                    
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
?>
