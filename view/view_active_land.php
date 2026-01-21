<div id="package">
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['assigned'])){
        $assigned = htmlspecialchars(stripslashes($_GET['assigned']));
       
    $get_details = new selects();
    //get assigned id
    $ass = $get_details->fetch_details_cond('assigned_fields','assigned_id', $assigned);
    foreach($ass as $as){
        $customer_id = $as->customer;
    }
    //get customer details
    $cus = $get_details->fetch_details_group('customers', 'customer', 'customer_id', $customer_id);
    $client = $cus->customer;
     
    //check for current loan
    $rows = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $assigned);
    if(is_array($rows)){
        foreach($rows as $row){
        
    

?>
<div class="info" style="margin:0!important; width:90%!important"></div>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed" href="javascript:void(0)" onclick="showPage('land_assigned_report.php')"><i class="fas fa-angle-double-left"></i> Return</a>
    <div class="displays allResults" style="width:100%;">
    <section id="prescriptions">
            <div class="add_user_form" style="margin:0!important;width:100%!important">
                <h3 style="background:var(--otherColor);color:#fff;text-align:center;font-size:.9rem;padding:5px">Field Purchase Details for <?php echo $client?></h3>
                <section style="text-align:left;">
                    <div class="inputs" style="align-items:flex-end; justify-content:left; gap:.5rem; padding:0!important">
                       <div class="data" style="width:24%;">
                            <label for="loan_name">Field:</label>
                            <?php
                                $prods = $get_details->fetch_details_cond('fields', 'field_id', $row->field);
                                if(is_array($prods)){
                                    foreach($prods as $prod){
                                        $product_name = $prod->field_name;
                                    }
                                }
                            ?>
                            <input type="text" value="<?php echo $product_name?>" readonly>
                       </div>
                       <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Size</label>
                            <input type="text" value="<?php echo number_format($row->field_size, 2)?>plot (<?php echo number_format($row->field_size * 500)?>m&sup2)" readonly>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Purchase Cost (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->purchase_cost, 2)?>" readonly>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Discount (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->discount, 2)?>" readonly style="color:green">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Total Due (₦)</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->total_due, 2)?>" readonly style="color:red">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Purchase Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y, h:ia", strtotime($row->assigned_date))?>" readonly>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Start Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y, h:ia", strtotime($row->start_date))?>" readonly>
                        </div>
                        
                        <div class="data" style="width:24%;">
                            <label for="duration" style="text-align:left!important;">Payment Duration</label>
                            <?php
                                if($row->payment_duration == 1){
                                    $pay_duration = "Outright Purhase";
                                }else{
                                    $pay_duration = $row->payment_duration." months";
                                }
                            ?>
                            <input type="text" value="<?php echo $pay_duration?> " readonly>
                        </div>
                        <!-- <div class="data" style="width:24%;">
                            <label for=""> Installment:</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->installment, 2)?>" readonly style="color:var(--otherColor)">
                        </div> -->
                        <div class="data" style="width:24%;">
                            <label for="repayment" style="text-align:left!important;">Rent Contract Duration</label>
                            <input type="text" value="<?php echo $row->contract_duration?> Years" readonly>
                        </div>
                        
                        
                        <div class="data" style="width:24%;">
                            <label for="">Annual Rent:</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->annual_rent, 2)?>" readonly style="color:var(--tertiaryColor)">
                        </div>
                        
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Payment Due Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y", strtotime($row->due_date))?>" readonly>
                        </div>
                        
                    </div>
                </section>   
            </div>
        </section>
        <section style="width:100%">
            
                <?php
                    //get total due
                    $tls = $get_details->fetch_sum_single('field_payment_schedule', 'amount_due', 'assigned_id', $row->assigned_id);
                    foreach($tls as $tl){
                        $total_due = $tl->total;
                    }
                    //get total paid
                    $paids = $get_details->fetch_sum_single('field_payment_schedule', 'amount_paid', 'assigned_id', $row->assigned_id);
                    foreach($paids as $paid){
                        $total_paid = $paid->total;
                    }
                    $balance = $total_due - $total_paid;
                    //get latest schedule
                    $sches = $get_details->fetch_details_2cond('field_payment_schedule', 'assigned_id', 'payment_status', $row->assigned_id, 0);
                    if(is_array($sches)){
                        foreach($sches as $sche){
                            $schedule = $sche->repayment_id;
                        }
                    }
                ?>
                    
                    <div class="totals" style="display:flex; gap:1rem; justify-content:flex-end; align-items:center; padding:10px;">
                        <!-- <a href="javascript:void(0)" title="Generate New Schedule" onclick="showPage('field_payment.php?schedule=<?php echo $schedule?>&customer=<?php echo $customer_id?>')" style="background:var(--tertiaryColor); color:#fff; padding:5px 10px; border-radius:15px; box-shadow:1px 1px 1px #222; border:1px solid #fff;">Post Payment <i class="fas fa-hand-holding-dollar"></i></a> -->
                        <?php
                        echo "<p class='total_amount' style='background:green; color:#fff; text-decoration:none; width:auto; float:right; padding:10px;font-size:1rem;'>Total Paid: ₦".number_format($total_paid, 2)."</p>";
                        echo "<p class='total_amount' style='background:red; color:#fff; text-decoration:none; width:auto; float:right; padding:10px;font-size:1rem;'>Total Due: ₦".number_format($balance, 2)."</p>";
                    ?>
                    </div>
                
            </div>
        </section>
    </div>
    
</div>
<?php
        }
            }else{
                ?>
                <div class="not_available"><p><strong><i class="fas fa-exclamation-triangle" style="color: #cfb20e;"></i> No Active Field</strong><br>The selected customer have no active Field. Cannot proceed!</p>
                <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('post_field_payment.php')"><i class="fas fa-angle-double-left"></i> Return</a></div>
        <?php
            }
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>