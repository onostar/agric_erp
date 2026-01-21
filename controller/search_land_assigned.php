<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_revenue = new selects();
    $details = $get_revenue->fetch_details_date('assigned_fields', 'date(assigned_date)', $from, $to);
    $n = 1;
?>
<h2>Lands/Fields Assigned between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Assigned Land report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
		        <td>S/N</td>
                <td>Client</td>
                <td>Field</td>
                <td>Size</td>
                <td>Purchase Cost</td>
                <td>Discount</td>
                <td>Total Due</td>
                <td>Status</td>
                <td>Date</td>
                <td>Posted By</td>
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
                        //get client name
                        $client = $get_revenue->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
                        echo $client->customer;
                    ?>  
                </td>
                <td>
                    <?php
                        //get field name
                        //get field from assigned_id first
                       
                        $fields = $get_revenue->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $fields->field_name
                    ?>
                </td>
                <td><?php echo $detail->field_size?> plot(<?php echo $detail->field_size * 500?>m²)</td>
                <td style="color:green"><?php  echo "₦".number_format($detail->purchase_cost, 2);?></td>
                <td><?php  echo "₦".number_format($detail->discount, 2);?></td>
                <td style="color:red"><?php  echo "₦".number_format($detail->total_due, 2);?></td>
                <td style="color:var(--primaryColor)">
                    <?php
                        if($detail->contract_status == 1){
                            echo "Pending";
                        }elseif($detail->contract_status == 2){
                            echo "Fully Paid";
                        }
                    ?>
                </td>
                
                <td>
                    <?php echo date("d-M-Y", strtotime($detail->assigned_date))?>
                </td>
                <td>
                    <?php
                        //get posted by
                        $pst = $get_revenue->fetch_details_group('users', 'full_name', 'user_id', $detail->assigned_by);
                        echo $pst->full_name;
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)"  onclick="showPage('view_active_land.php?assigned=<?php echo $detail->assigned_id;?>')"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="view details"><i class="fas fa-eye"></i></a>
                    
                </td>
                
            </tr>
            <?php $n++; }}?>
        </tbody>
    </table>
<?php
    if(gettype($details) == "string"){
        echo "<p class='no_result'>'$details'</p>";
    }
    //get total cos of payments today
    $ttls = $get_revenue->fetch_sum_2date('assigned_fields', 'total_due', 'date(assigned_date)', $from, $to);
    if(gettype($ttls) === 'array'){
        foreach($ttls as $ttl){
            echo "<p class='total_amount' style='color:green; text-align:center;'>Total: ₦".number_format($ttl->total, 2)."</p>";
        }
    }
?>
