<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['customer'])){
        $customer_id = htmlspecialchars(stripslashes($_GET['customer']));
    //get customer details
    $get_customer = new selects();
    $cus = $get_customer->fetch_details_group('customers', 'customer', 'customer_id', $customer_id);
    $client = $cus->customer;
?>
<style>
    table td{
        font-size:.75rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="add_room" class="displays" style="width:95%!important; margin:10px auto!important">
    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; margin:10px 0!important; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('view_customer_investments.php')"><i class="fas fa-angle-double-left"></i> Return</a>
    <h3 style="background:var(--tertiaryColor); color:#fff; text-align:center!important; font-size:.9rem; padding:5px;">Showing all investments for <?php echo $client?></h3>
    
   
<div class="displays allResults new_data" id="revenue_report" style="width:100%!important">
    <h2>Land/Fields </h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', '<?php echo $client?> Lands')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
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
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condOrder('assigned_fields', 'customer', $customer_id, 'assigned_date');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
               
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
                    <a href="javascript:void(0)"  onclick="showPage('view_customer_fields.php?assigned=<?php echo $detail->assigned_id;?>')"style="color:#fff; background:var(--otherColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="view details"><i class="fas fa-eye"></i></a>
                    
                </td>
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
       /*  //get total cos of payments today
        $ttls = $get_details->fetch_sum_curdate('assigned_fields', 'total_due', 'assigned_date');
        if(gettype($ttls) === 'array'){
            foreach($ttls as $ttl){
                echo "<p class='total_amount' style='color:green; text-align:center;'>Total: ₦".number_format($ttl->total, 2)."</p>";
            }
        } */
    ?>
       
</div>
<div class="displays allResults new_data" id="revenue_report" style="width:100%!important">
    <h2>Concentrate Investments</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', '<?php echo $client?> concentrate Investments')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Date</td>
                <td>Inv. No.</td>
                <td>Currency</td>
                <td>Units</td>
                <td>Amount</td>
                <td>Value in USD</td>
                <td>Amount Paid</td>
                <td>Amount Due</td>
                <td>Status</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_details = new selects();
                $details = $get_details->fetch_details_condOrder('investments', 'customer', $customer_id, 'post_date');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo date("d-M-Y, h:ia", strtotime($detail->post_date))?></td>
               
                <td style="color:var(--primaryColor)"><?php echo "DAV/CON/00$detail->investment_id"?></td>
                <td>
                    <?php
                        echo $detail->currency;
                    ?>
                </td>
                <td><?php echo $detail->units?> unit(s)</td>
                <?php
                    if($detail->currency == "Dollar"){
                ?>
                <td style="color:var(--otherColor)"><?php echo "$".number_format($detail->amount, 2);?></td>
                <?php }else{?>
                <td style="color:var(--otherColor)"><?php echo "₦".number_format($detail->amount, 2);?></td>
                <?php }?>
                <td style="color:var(--otherColor)"><?php echo "$".number_format($detail->total_in_dollar, 2);?></td>
                <td style="color:green">
                    <?php
                         //total paid
                       $paids = $get_details->fetch_sum_single('investment_payments', 'amount', 'investment', $detail->investment_id);
                       if(is_array($paids)){
                           foreach($paids as $paid){
                               $total_paid = $paid->total;
                           }
                        }else{
                            $total_paid = 0;
                        }
                        if($detail->currency == "Dollar"){
                            echo "$".number_format($total_paid, 2);
                        }else{
                            echo "₦".number_format($total_paid, 2);
                        }
                    ?>
                </td>
                <td style="color:red">
                    <?php 
                       
                        $debt = $detail->amount - $total_paid;
                        if($detail->currency == "Dollar"){
                            echo "$".number_format($debt, 2);
                        }else{
                            echo "₦".number_format($debt, 2);
                        }
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
                    <a href="javascript:void(0)"  onclick="showPage('view_client_conc_investment.php?investment=<?php echo $detail->investment_id?>&customer=<?php echo $detail->customer?>')" style="color:#fff; background:var(--tertiaryColor); padding:5px; border:1px solid #fff; box-shadow:1px 1px 1px #222; border-radius:15px;" title="View details"><i class="fas fa-eye"></i></a>
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
        /* $ttls = $get_details->fetch_sum_single('investments', 'total_in_dollar', 'customer', $customer_id);
        if(gettype($ttls) === 'array'){
            foreach($ttls as $ttl){
                echo "<p class='total_amount' style='color:green; text-align:center;'>Total in USD: $".number_format($ttl->total, 2)."</p>";
            }
        } */
    ?>
       
</div>
</div>
<?php
       
    }
    }else{
        echo "<p class='exist'>Your session has expired. You are not logged in</p>";
        header("Location: ../index.php");
    }
?>