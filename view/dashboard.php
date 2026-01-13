<div id="general_dashboard">
<div class="dashboard_all">
    <?php if($role !== "Staff"){?>
    <h3><i class="fas fa-home"></i> Dashboard for <span style="color:var(--secondaryColor);font-size:1rem"><?php echo $store?></span></h3>
    <?php 
    }
        $get_prds = new selects();
        if($role === "Admin" || $role === "Accountant"){
    ?>
    
    <div id="dashboard">
        <div class="cards" id="card4">
            <a href="javascript:void(0)" onclick="showPage('sales_report.php')">
                <div class="infos">
                    <p><i class="fas fa-coins"></i> Monthly Sales</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_sum_curMonth2con('sales', 'total_amount', 'post_date', 'store', $store_id, 'sales_status', 2);
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                                $amount = $row->total;
                            }
                        }
                        if(gettype($rows) == 'string'){
                            $amount = 0;
                        }
                        
                        echo "₦".number_format($amount, 2);

                        
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card1">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-clipboard-list"></i> Production cost</p>
                    <p>
                    <?php
                        if($store_id == "1"){
                            //first get labour cost for farm
                            $labs = $get_prds->fetch_sum_curMonth1con1neg('tasks', 'labour_cost', 'post_date', 'farm', $store_id, 'cycle', 0);
                            if(is_array($labs)){
                                foreach($labs as $lab){
                                    $labour_total = $lab->total;
                                }
                            }else{
                                $labour_total = 0;
                            }
                            //then get raw materials cost for farm
                            $inps = $get_prds->fetch_sum_curMonth1con('task_items', 'total_cost', 'post_date', 'farm', $store_id);
                            $total = 0;
                            if(is_array($inps)){
                                foreach($inps as $inp){
                                    $inputs_total = $inp->total;
                                }
                            }else{
                                $inputs_total = 0;
                            }
                            $farm_production = $labour_total + $inputs_total;
                            /* $prds = $get_prds->fetch_sum_2colCurDate1Con('production', 'raw_quantity', 'unit_cost', 'date(post_date)', 'store', $store_id);
                            if(is_array($prds)){
                                foreach($prds as $prd){
                                    $production = $prd->total;
                                }
                            }else{
                                $production = 0;
                            } */
                            // $total_production = $farm_production + $production;
                            echo "₦".number_format($farm_production, 2);
                           
                        }else if($store_id == "10"){
                            $prds = $get_prds->fetch_sum_curMonth1con('concentrate_production', 'total_pineapple_cost', 'date_produced', 'store', $store_id);
                            if(gettype($prds) === 'array'){
                                foreach($prds as $prd){
                                    echo "₦".number_format($prd->total, 2);
                                }
                            }
                            if(gettype($prds) == "string"){
                                echo "₦0.00";
                            }
                        }else{
                            $prds = $get_prds->fetch_monthly_briquette($store_id);
                            if(gettype($prds) === 'array'){
                                foreach($prds as $prd){
                                    echo "₦".number_format($prd->total, 2);
                                }
                            }
                            if(gettype($prds) == "string"){
                                echo "₦0.00";
                            }
                        }
                    
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card5" style="background: var(--moreColor)">
            <a href="javascript:void(0)" class="page_navs" onclick="showPage('expense_report.php')">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Monthly Expense</p>
                    <p>
                    <?php
                        $get_exp = new selects();
                        $exps = $get_exp->fetch_sum_curMonth1con('expenses', 'amount', 'date(expense_date)', 'store', $store_id);
                        foreach($exps as $exp){
                            echo "₦".number_format($exp->total, 2);
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <?php if($store_id == 1){?>
            <div class="cards" id="card0">
            <a href="javascript:void(0)" onclick="showPage('active_loans.php')"class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Rent Receivables</p>
                    <p>
                    <?php
                        //get total due amount
                        $dues = $get_prds->fetch_sum_single('rent_schedule', 'amount_due', 'payment_status', 0);
                        if(is_array($dues)){
                            foreach($dues as $due){
                                $amount_due = $due->total;
                            }
                        }else{
                            $amount_due = 0;
                        }
                        //get total paid amount
                        $paid = $get_prds->fetch_sum_single('rent_schedule', 'amount_paid', 'payment_status', 0);
                        if(is_array($paid)){
                            foreach($paid as $pay){
                                $amount_paid = $pay->total;
                            }
                        }else{
                            $amount_paid = 0;
                        }
                        $debt = $amount_due - $amount_paid;
                        echo "₦".number_format($debt, 2);
                        
                    ?>
                    </p>
                </div>
            </a>
            
        </div> 
        <?php }else{?>
        <div class="cards" id="card0">
           
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-calendar"></i> Receivables</p>
                    <p>
                    <?php
                        $receivables = new selects();
                        /* $dues = $receivables->fetch_sum_double('debtors', 'amount', 'debt_status', 0, 'store', $store_id); */
                        $dues = $receivables->fetch_receivables($store_id);
                        foreach($dues as $due){
                            $total_due = $due->total_due;
                        }
                        if($total_due < 0){
                            echo "₦0.00";
                        }else{
                            echo "₦".number_format($total_due, 2);
                        }
                        
                        
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <?php }?>
        
    </div>
    <?php
        }else if($designation === "HR MANAGER"){
    ?>
    <div id="dashboard">
        <div class="cards" id="card1">
            <a href="javascript:void(0)" onclick="showPage('attendance_report.php')" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-users"></i> Staff Present</p>
                    <p>
                    <?php
                        //get total customers
                       $get_cus = new selects();
                        $staffs =  $get_cus->fetch_staff_present($store_id);
                        //get total staff
                        $all_staff = $get_cus->fetch_count_2cond('staffs', 'store', $store_id, 'staff_status', 0);
                        echo $staffs." / ". $all_staff;
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card4">
            <a href="javascript:void(0)" >
                <div class="infos">
                    <p><i class="fas fa-user-xmark"></i> Absent Today</p>
                    <p>
                    <?php
                         //get total customers
                       $get_cus = new selects();
                        $staffs =  $get_cus->fetch_staff_present($store_id);
                        //get total staff
                        $all_staff = $get_cus->fetch_count_2cond('staffs', 'store', $store_id, 'staff_status', 0);
                        $absent = $all_staff - $staffs;
                        echo $absent;
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card0">
            <a href="javascript:void(0)" onclick="showPage('approve_leave.php')">
                <div class="infos">
                    <p><i class="fas fa-calendar-minus"></i> Leave Request</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $leaves = $get_sales->fetch_count_2cond('leaves', 'store', $store_id, 'leave_status', 0);
                        echo $leaves." Pending";
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        
        <div class="cards" id="card2" style="background: var(--moreColor)">
        <a href="javascript:void(0)" onclick="showPage('attendance_report.php')"class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-money-check"></i> Late arrivals</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $late = $get_sales->fetch_late_arrivals($store_id);
                        echo $late." today";
                    ?>
                    </p>
                </div>
            </a>
        </div> 
            
    </div>
    <?php
        }else{
    ?>
    <div id="dashboard">
    <div class="cards" id="card1">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-briefcase"></i> Days at Work</p>
                    <p>
                    <?php
                        //get total customers
                       $get_cus = new selects();
                       $workdays =  $get_cus->fetch_staff_work_days($staff_id);
                       echo $workdays." day(s)";
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card4">
            <a href="javascript:void(0)" >
                <div class="infos">
                    <p><i class="fas fa-calendar-minus"></i> Leave Request</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_details_cond('leaves', 'employee', $staff_id);
                        if(is_array($rows)){
                            foreach($rows as $row){
                                if($row->leave_status == 0 && $row->returned == NULL){
                                    echo "Pending";
                                }else if($row->leave_status == 1 && $row->returned == NULL){
                                    echo "Approved";
                                }else if($row->leave_status == -1 && $row->returned == NULL){
                                    echo "Declined";
                                }
                            }
                        }else{
                            echo "No request";
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card3">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-money-check"></i> Products</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_sum_double('inventory', 'quantity', 'store', $store_id, 'item_type', 'Product');
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                            echo $row->total."kg";

                            }
                        }
                        if(gettype($rows) == 'string'){
                            echo "0kg";
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card2" style="background: var(--moreColor)">
        <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-money-check"></i> Consumables</p>
                    <p>
                    <?php
                        $get_sales = new selects();
                        $rows = $get_sales->fetch_sum_double('inventory', 'quantity', 'store', $store_id, 'item_type', 'Consumable');
                        if(gettype($rows) == 'array'){
                            foreach($rows as $row){
                            echo $row->total;

                            }
                        }
                        if(gettype($rows) == 'string'){
                            echo "0";
                        }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
            
    </div>
    <?php }?>
</div>
<?php 
    if($role === "Admin" || $role == "Accountant"){
?>
<!-- management summary -->
<div id="paid_receipt" class="management">
    <hr>
    <div class="daily_monthly">
        <!-- daily revenue summary -->
        <div class="daily_report allResults">
            <h3 style="background:var(--otherColor)">Daily Encounters</h3>
            <table style="box-shadow:none">
                <thead>
                    <tr>
                        <td>S/N</td>
                        <td>Date</td>
                        <td>Customers</td>
                        <td>Revenue</td>
                    </tr>
                </thead>
                <?php
                    $n = 1;
                    $get_daily = new selects();
                    $dailys = $get_daily->fetch_daily_sales($store_id);
                    if(gettype($dailys) == "array"){
                    foreach($dailys as $daily):

                ?>
                <tbody>
                    <tr>
                        <td><?php echo $n?></td>
                        <td><?php echo date("jS M, Y",strtotime($daily->post_date))?></td>  
                        <td style="text-align:center; color:var(--otherColor)"><?php echo $daily->customers?></td>
                        <td style="color:green;"><?php echo "₦".number_format($daily->revenue)?></td>
                    </tr>
                </tbody>
                <?php $n++; endforeach; }?>

                
            </table>
            <?php
                if(gettype($dailys) == "string"){
                    echo "<p class='no_result'>'$dailys'</p>";
                }
            ?>
        </div>
        <!-- monthly revenue summary -->
        <div class="monthly_report allResults">
            <div class="chart">
                <!-- chart for technical group -->
                <?php
                $get_monthly = new selects();
                $monthlys = $get_monthly->fetch_monthly_sales($store_id);
                if(gettype($monthlys) == "array"){
                    foreach($monthlys as $monthly){
                        $revenue[] = $monthly->revenue;
                        $month[] = date("M, Y", strtotime($monthly->post_date));
                    }
                }
                ?>
                <h3 style="background:var(--moreColor)">Monthly statistics</h3>
                <canvas id="chartjs_bar2"></canvas>
            </div>
            <div class="monthly_encounter">
                <h3 style="background:var(--otherColor)">Monthly Encounters</h3>
                <table>
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Month</td>
                            <td>Customers</td>
                            <td>Amount</td>
                            <td>Daily Average</td>
                        </tr>
                    </thead>
                    <?php
                        $n =1;
                        $get_monthly = new selects();
                        $monthlys = $get_monthly->fetch_monthly_sales($store_id);
                        if(gettype($monthlys) == "array"){
                        foreach($monthlys as $monthly):

                    ?>
                    <tbody>
                        <tr>
                            <td><?php echo $n?></td>
                            <td><?php echo date("M, Y", strtotime($monthly->post_date))?></td>
                            <td style="text-align:center; color:var(--otherColor"><?php echo $monthly->customers?></td>
                            <td style="text-align:center; color:green"><?php echo "₦".number_format($monthly->revenue)?></td>
                            <td style="text-align:center; color:red"><?php
                                $average = $monthly->revenue/$monthly->daily_average;
                                echo "₦".number_format($average, 2);
                            ?></td>
                        </tr>
                    </tbody>
                    <?php $n++; endforeach; }?>

                    
                </table>
                <?php 
                    if(gettype($monthlys) == "string"){
                        echo "<p class='no_result'>'$monthlys'</p>";
                    }
                ?>
            </div>
        </div>
        
    </div>
</div>

<?php 
    }else{
?>
<div class="check_out_due">
    <hr>
    <div class="displays allResults" id="check_out_guest">
       
        <h3 style="background:var(--otherColor)">My Daily Tasks</h3>
        <table id="check_out_table" class="searchTable" style="width:100%;">
            <thead>
                <tr style="background:var(--moreColor)">
                    <td>S/N</td>
                    <td>Invoice</td>
                    <td>Item</td>
                    <td>Qty</td>
                    <td>Unit sales</td>
                    <td>Amount</td>
                    <td>Payment mode</td>
                    <td>Time</td>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_date2Cond('sales', 'date(post_date)', 'sales_status', 2, 'posted_by', $user_id);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:green"><?php echo $detail->invoice?></td>
                    <td>
                        <?php
                            $get_name = new selects();
                            $name = $get_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                            echo $name->item_name;
                        ?>
                    </td>
                    <td style="text-align:center; color:var(--otherColor)"><?php echo $detail->quantity?>kg</td>
                    <td><?php echo "₦".number_format($detail->price)?></td>
                    <td><?php echo "₦".number_format($detail->total_amount)?></td>
                    <td>
                        <?php
                            //get payment mode
                            $get_mode = new selects();
                            $mode = $get_mode->fetch_details_group('payments', 'payment_mode', 'invoice', $detail->invoice);
                            //check if invoice is more than 1
                            $get_mode_count = new selects();
                            $rows = $get_mode_count->fetch_count_cond('payments', 'invoice', $detail->invoice);
                                if($rows >= 2){
                                    echo "Multiple payment";
                                }else{
                                    echo $mode->payment_mode;

                                }
                            ?>
                    </td>
                    <td><?php echo date("h:i:sa", strtotime($detail->post_date))?></td>
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
</div>
<?php
    }
?>
</div>