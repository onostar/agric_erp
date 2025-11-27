<div id="general_dashboard">
<div class="dashboard_all">
    <h3><i class="fas fa-home"></i> My <span style="color:var(--secondaryColor);font-size:1rem">Dashboard</h3>
    <?php
        $get_info = new selects();
    ?>
    <div id="dashboard">
        <div class="cards" id="card4">
            <a href="javascript:void(0)" onclick="showPage('customer_field.php')">
                <div class="infos">
                    <p><i class="fas fa-tree"></i> My fields</p>
                    <p>
                    <?php
                        
                        $fields = $get_info->fetch_count_cond('fields', 'customer', $user_id); echo $fields
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card1">
            <a href="javascript:void(0)" class="page_navs" onclick="showPage('my_investments.php')">
                <div class="infos">
                    <p><i class="fas fa-briefcase"></i> Active Investments</p>
                    <p>
                    <?php
                        $invests = $get_info->fetch_sum_double('investments', 'total_in_naira', 'customer', $user_id, 'contract_status', 1);
                        if(is_array($invests)){
                            foreach($invests as $invest){
                                $total_investment = $invest->total;
                            }
                        }else{
                            $total_investment = 0;
                        }
                        echo "₦".number_format($total_investment, 2);
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card5" style="background: var(--moreColor)">
            <a href="javascript:void(0)" onclick="showPage('loan_status.php')">
                <div class="infos">
                    <p><i class="fas fa-piggy-bank"></i> Payment Due</p>
                    <p>
                    <?php
                       //balance
                       $oweds = $get_info->fetch_sum_double('field_payment_schedule', 'amount_due', 'payment_status', 0, 'customer', $user_id);
                       if(is_array($oweds)){
                           foreach($oweds as $owed){
                               $balance_due = $owed->total;
                           }
                        }else{
                            $balance_due = 0;
                        }
                        //get total paid amount
                        $paid = $get_info->fetch_sum_double('field_payment_schedule', 'amount_paid', 'payment_status', 0, 'customer', $user_id);
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
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card0">
            
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Receivables</p>
                    <p>
                    <?php
                       //balance
                       $oweds = $get_info->fetch_sum_double('rent_schedule', 'amount_due', 'payment_status', 0, 'customer', $user_id);
                       if(is_array($oweds)){
                           foreach($oweds as $owed){
                               $balance_due = $owed->total;
                           }
                        }else{
                            $balance_due = 0;
                        }
                        //get total paid amount
                        $paid = $get_info->fetch_sum_double('rent_schedule', 'amount_paid', 'payment_status', 0, 'customer', $user_id);
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
                    </p>
                </div>
            </a>
        </div> 
        
        
    </div>
   
</div>

<!-- management summary -->
<div class="check_out_due" style="width:90%; margin-top:20px">
    <hr>
    <div class="daily_monthly" style="margin:0!important;padding:0!important">
        <!-- daily revenue summary -->
        <div class="daily_report allResults" style="margin:0!important;padding:0!important">
            <h3 style="background:var(--otherColor); font-family:Poppins">Scheduled Payments</h3>
            <table id="item_list_table" class="searchTable">
                <thead>
                    <tr>
                        <td>S/N</td>
                        <td>Due Date</td>
                        <td>Amount Due</td>
                        <td>Status</td>
                    </tr>
                </thead>
                <tbody id="result">
                    <?php
                        $n = 1;
                        $loans = $get_info->fetch_details_2cond('assigned_fields', 'customer', 'contract_status', $user_id, 1);
                        if(is_array($loans)){
                            foreach($loans as $loan){
                                $repays = $get_info->fetch_details_2cond('field_payment_schedule', 'field', 'payment_status', $loan->field, 0);
                                if(is_array($repays)){
                                $allow_next = true; // True until first unpaid schedule is found

                                foreach($repays as $repay){
                        
                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td><?php echo date("d-M-Y", strtotime($repay->due_date))?></td>
                        <td style="color:var(--secondaryColor)"><?php  echo "₦".number_format(($repay->amount_due - $repay->amount_paid), 2)?></td>
                        <td>
                            <?php
                                $date_due = new DateTime($repay->due_date);
                                $today = new DateTime();

                                $button = "<a style='border-radius:15px; background:var(--tertiaryColor);color:#fff; padding:3px 6px; box-shadow:1px 1px 1px #222; border:1px solid #fff' href='javascript:void(0)' title='Post payment'>Pay <i class='fas fa-hand-holding-dollar'></i></a>";

                                if($repay->payment_status == "1"){
                                    echo "<span style='color:var(--tertiaryColor);'>Paid <i class='fas fa-check-circle'></i></span>";
                                } else {
                                    // First unpaid schedule (or any overdue) is allowed to pay only if previous schedules are paid
                                    if($allow_next || $date_due < $today){
                                        if($date_due > $today){
                                            echo "<span style='color:var(--primaryColor);'><i class='fas fa-spinner'></i> Pending </span> ";
                                        } else {
                                            echo "<span style='color:red;'><i class='fas fa-clock'></i> Overdue </span> ";
                                        }
                                        $allow_next = false; // After showing Add Payment for one, others must wait
                                    } else {
                                        echo "<span style='color:#999;'>Waiting for previous payment <i class='fas fa-lock'></i></span>";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    
                    <?php $n++; };}}}?>
                </tbody>
            </table>
            <?php
                if(gettype($loans) == "string"){
                    echo "<p class='no_result'>'$loans'</p>";
                }
            ?>
        </div>
        <!-- monthly revenue summary -->
        <div class="monthly_report allResults" style="margin:0!important;padding:0!important">
            
            <div class="monthly_encounter" style="margin:0 0 20px; width:100%!important">
                <h3 style="background:rgb(117, 32, 12)!important; font-family:Poppins">Rent Schedule</h3>
                <!-- <table>
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Amount</td>
                            <td>Details</td>
                        </tr>
                    </thead>
                    <?php
                       /*  $n = 1;
                        $trxs = $get_info->fetch_details_condLimitDesc('deposits', 'customer', $user_id, 10, 'post_date');
                        if(is_array($trxs)){
                        foreach($trxs as $trx): */

                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $n?></td>
                            <td style="color:var(--primaryColor)"><?php echo date("d-M-Y, h:ia", strtotime($trx->post_date))?></td>
                            <td style="text-align:center; color:green"><?php echo "₦".number_format($trx->amount)?></td>
                            <td><?php
                                echo $trx->trx_type;
                            ?></td>
                        </tr>
                    </tbody>
                    <?php /* $n++; endforeach; } */?>

                    
                </table>
                <?php 
                    /* if(gettype($trxs) == "string"){
                        echo "<p class='no_result'>'$trxs'</p>";
                    } */
                ?> -->
                <table>
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Date</td>
                            <td>Amount</td>
                            <td>Details</td>
                        </tr>
                    </thead>
                    
                <tbody id="result">
                    <?php
                        $n = 1;
                        $loans = $get_info->fetch_details_2cond('assigned_fields', 'customer', 'contract_status', $user_id, 2);
                        if(is_array($loans)){
                            foreach($loans as $loan){
                                $repays = $get_info->fetch_details_2cond('rent_schedule', 'field', 'payment_status', $loan->field, 0);
                                if(is_array($repays)){
                                $allow_next = true; // True until first unpaid schedule is found
                                foreach($repays as $repay){
                        
                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        <td><?php echo date("d-M-Y", strtotime($repay->due_date))?></td>
                        <td style="color:var(--secondaryColor)"><?php  echo "₦".number_format(($repay->amount_due - $repay->amount_paid), 2)?></td>
                        <td>
                            <?php
                                $date_due = new DateTime($repay->due_date);
                                $today = new DateTime();

                                $button = "<a style='border-radius:15px; background:var(--tertiaryColor);color:#fff; padding:3px 6px; box-shadow:1px 1px 1px #222; border:1px solid #fff' href='javascript:void(0)' onclick=\"showPage('client_payment.php?schedule={$repay->repayment_id}&customer={$user_id}')\" title='Post payment'>Pay <i class='fas fa-hand-holding-dollar'></i></a>";

                                if($repay->payment_status == "1"){
                                    echo "<span style='color:var(--tertiaryColor);'>Paid <i class='fas fa-check-circle'></i></span>";
                                } else {
                                    // First unpaid schedule (or any overdue) is allowed to pay only if previous schedules are paid
                                    if($allow_next || $date_due < $today){
                                        if($date_due > $today){
                                            echo "<span style='color:var(--primaryColor);'><i class='fas fa-spinner'></i> Pending </span> ";
                                        } else {
                                            echo "<span style='color:red;'><i class='fas fa-clock'></i> Overdue </span> ";
                                        }
                                        $allow_next = false; // After showing Add Payment for one, others must wait
                                    } else {
                                        echo "<span style='color:#999;'>Waiting for previous payment <i class='fas fa-lock'></i></span>";
                                    }
                                }
                            ?>
                        </td>
                    </tr>
                    
                    <?php $n++; };}}}?>
                </tbody>
            </table>
            <?php
                if(gettype($loans) == "string"){
                    echo "<p class='no_result'>'$loans'</p>";
                }
            ?>
            </div>
           
        </div>
        
    </div>
</div>
