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
<div id="farm_fields" class="displays management" style="margin:0!important;width:100%!important">
   
<div class="displays allResults new_data" id="revenue_report">
    <h2>Land/Fields Pending Payment</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Todays Land Assigned')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
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
                <td>Documentation</td>
                <td>Date</td>
                <td>Posted By</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_pending_field_purchase();
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php
                        //get client name
                        $client = $get_details->fetch_details_group('customers', 'customer', 'customer_id', $detail->customer);
                        echo $client->customer;
                    ?>  
                </td>
                <td>
                    <?php
                        //get field name
                        //get field from assigned_id first
                       
                        $fields = $get_details->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $fields->field_name
                    ?>
                </td>
                <td><?php echo $detail->field_size?> plot(<?php echo $detail->field_size * 500?>m²)</td>
                <td style="color:green"><?php  echo "₦".number_format($detail->purchase_cost, 2);?></td>
                <td><?php  echo "₦".number_format($detail->discount, 2);?></td>
                <td style="color:red"><?php  echo "₦".number_format($detail->total_due, 2);?></td>
                <td style="color:var(--otherColor)"><?php  echo "₦".number_format($detail->documentation, 2);?></td>
                <!-- <td style="color:var(--primaryColor)">
                    <?php
                        /* if($detail->contract_status == 1){
                            echo "Pending";
                        }elseif($detail->contract_status == 2){
                            echo "Fully Paid";
                        } */
                    ?>
                </td> -->
                
                <td>
                    <?php echo date("d-M-Y, h:ia", strtotime($detail->assigned_date))?>
                </td>
                <td>
                    <?php
                        //get posted by
                        $pst = $get_details->fetch_details_group('users', 'full_name', 'user_id', $detail->assigned_by);
                        echo $pst->full_name;
                    ?>
                </td>
                <td>
                    <a href="javascript:void(0)"  onclick="showPage('update_assigned_field.php?assigned=<?php echo $detail->assigned_id;?>')"style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="update details"><i class="fas fa-edit"></i></a>
                    
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