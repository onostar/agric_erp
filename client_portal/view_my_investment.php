<div id="package">
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['customer']) && isset($_GET['investment'])){
        $customer_id = htmlspecialchars(stripslashes($_GET['customer']));
        $investment = htmlspecialchars(stripslashes($_GET['investment']));
    //get customer details
    $get_details = new selects();
    $cus = $get_details->fetch_details_group('customers', 'customer', 'customer_id', $customer_id);
    $client = $cus->customer;
    //check for investment details
    $rows = $get_details->fetch_details_cond('investments', 'investment_id', $investment);
    if(is_array($rows)){
        foreach($rows as $row){
    

?>
<div class="info" style="margin:0!important; width:90%!important"></div>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed" href="javascript:void(0)" onclick="showPage('my_investments.php')"><i class="fas fa-angle-double-left"></i> Return</a>
    <div class="displays allResults" style="width:100%;">
    <section id="prescriptions">
            <div class="add_user_form" style="margin:0!important;width:100%!important">
                <h3 style="background:var(--otherColor);color:#fff;text-align:center;font-size:.9rem;padding:5px">Investment Details for DAV/CON/00<?php echo $row->investment_id?></h3>
                <section style="text-align:left;">
                    <div class="inputs" style="align-items:flex-end; justify-content:left; gap:.5rem; padding:0!important">
                       <div class="data" style="width:24%;">
                            <label for="loan_name">Contract Duration:</label>
                            <input type="text" value="<?php echo $row->duration?> Years" readonly>
                       </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Currency</label>
                            <input type="text" value="<?php echo $row->currency?>" readonly style="color:green">
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Amount Invested</label>
                            <?php if($row->currency == "Dollar"){?>
                            <input type="text" value="<?php echo '$'.number_format($row->amount, 2)?>" readonly>
                            <?php }else{?>
                            <input type="text" value="<?php echo '₦'.number_format($row->amount, 2)?>" readonly>
                            <?php }?>
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Exchange rate</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->exchange_rate, 2)?>/$1.00" readonly style="color:green">
                           
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Value In Naira</label>
                            <input type="text" value="<?php echo '₦'.number_format($row->total_in_naira, 2)?>" readonly style="color:green">
                           
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="amount" style="text-align:left!important;">Expected Returns</label>
                            <?php
                                //get sum of expected investment returns
                                $rets = $get_details->fetch_sum_single('investment_returns', 'amount_due', 'investment_id', $investment);
                                if(is_array($rets)){
                                    foreach($rets as $ret){
                                        $expected_returns = $ret->total;
                                    }
                                }else{
                                    $expected_returns = 0;
                                }
                            ?>
                            <input type="text" value="<?php echo '₦'.number_format($expected_returns, 2)?>" readonly style="color:var(--primaryColor)">
                           
                        </div>
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Date Posted:</label>
                            <input type="text" value="<?php echo date("d-M-Y, h:ia", strtotime($row->post_date))?>" readonly>
                        </div>
                        <?php
                            //check last date for returns schedule
                            if($row->contract_status != 0){
                                $checks = $get_details->fetch_details_condLimitDesc('investment_returns','investment_id', $investment, 1, 'due_date');
                                foreach($checks as $check){
                                    $due_date = $check->due_date;
                                }
                        ?>
                        
                        <div class="data" style="width:24%;">
                            <label for="purpose" style="text-align:left!important;">Due Date:</label>
                            <input type="text" value="<?php echo date("d-M-Y", strtotime($due_date))?>" readonly>
                        </div>
                        <?php }?>
                    </div>
                </section>   
            </div>
        </section>
        <section style="width:100%">
             <h3 style="background:var(--labColor); text-align:center; color:#fff; font-size:.9rem;padding:5px;">Returns Schedule</h3>
            <div class="displays allResults" style="width:100%!important; margin:0!important">
                <table id="item_list_table" class="searchTable">
                    <thead>
                        <tr style="background:var(--tertiaryColor)">
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Amount Due</td>
                            <td>Amount Paid</td>
                            <td>Status</td>
                        </tr>
                    </thead>
                    <tbody id="result">
                        <?php
                            $n = 1;
                            $repays = $get_details->fetch_details_cond('investment_returns', 'investment_id', $investment);
                            if(is_array($repays)){
                            $allow_next = true; // True until first unpaid schedule is found
                            foreach($repays as $index => $repay){
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td><?php echo date("d-M-Y", strtotime($repay->due_date))?></td>
                            <td style="color:var(--secondaryColor)"><?php echo "₦".number_format($repay->amount_due, 2)?></td>
                            <td><?php echo "₦".number_format($repay->amount_paid, 2)?></td>
                            <td>
                                <?php
                                    $date_due = new DateTime($repay->due_date);
                                    $today = new DateTime();

                                    $button = "<a style='border-radius:15px; background:var(--tertiaryColor);color:#fff; padding:3px 6px; box-shadow:1px 1px 1px #222; border:1px solid #fff' href='javascript:void(0)' onclick=\"showPage('investment_returns_payment.php?schedule={$repay->schedule_id}&customer={$customer_id}')\" title='Post payment'>Add Payment <i class='fas fa-hand-holding-dollar'></i></a>";

                                    if($repay->payment_status == "1"){
                                        echo "<span style='color:var(--tertiaryColor);'>Paid <i class='fas fa-check-circle'></i></span>";
                                    } else {
                                        // First unpaid schedule (or any overdue) is allowed to pay only if previous schedules are paid
                                        if($allow_next || $date_due < $today){
                                            if($date_due > $today){
                                                echo "<span style='color:var(--primaryColor);'><i class='fas fa-spinner'></i> Pending </span>";
                                            } else {
                                                echo "<span style='color:red;'><i class='fas fa-clock'></i> Overdue </span>";
                                            }
                                            $allow_next = false; // After showing Add Payment for one, others must wait
                                        } else {
                                            echo "<span style='color:#999;'>Waiting for previous payment <i class='fas fa-lock'></i></span>";
                                        }
                                    }
                                ?>
                            </td>
                        </tr>
                        <?php $n++; }; }?>
                    </tbody>
                </table>
                <?php
                    //get total due
                    $tls = $get_details->fetch_sum_single('investment_returns', 'amount_due', 'investment_id', $investment);
                    foreach($tls as $tl){
                        $total_due = $tl->total;
                    }
                    //get total paid
                    $paids = $get_details->fetch_sum_single('investment_returns', 'amount_paid', 'investment_id', $investment);
                    foreach($paids as $paid){
                        $total_paid = $paid->total;
                    }
                    $balance = $total_due - $total_paid;
                ?>
                    <div class="totals" style="display:flex; gap:1rem; justify-content:right">
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
                <div class="not_available"><p><strong><i class="fas fa-exclamation-triangle" style="color: #cfb20e;"></i> No Active Active investment!</p>
                <a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('my_investments.php')"><i class="fas fa-angle-double-left"></i> Return</a></div>
        <?php
            }
        }
    }else{
        echo "Your session has expired! Kindly Login to again to continue";
        header("Location: ../index.php");
    }
?>
</div>