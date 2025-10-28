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
            <a href="javascript:void(0)" class="page_navs" onclick="showPage('active_crop_cycles.php')">
                <div class="infos">
                    <p><i class="fas fa-seedling"></i> Active Crop Cycle</p>
                    <p>
                    <?php
                        $cycles = $get_info->fetch_count_2cond('fields', 'customer', $user_id, 'field_status', 1); echo $cycles
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card5" style="background: var(--moreColor)">
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-hand-holding-dollar"></i> Pending Payment</p>
                    <p>
                    <?php
                        /* $get_exp = new selects();
                        $exps = $get_exp->fetch_sum_curMonth1con('expenses', 'amount', 'date(expense_date)', 'store', $store_id);
                        foreach($exps as $exp){ */
                            echo "₦".number_format(0, 2);
                        // }
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        <div class="cards" id="card0">
            
            <a href="javascript:void(0)" class="page_navs">
                <div class="infos">
                    <p><i class="fas fa-calendar"></i> Receivables</p>
                    <p>
                    <?php
                       
                        
                    ?>
                    </p>
                </div>
            </a>
        </div> 
        
        
    </div>
   
</div>

<!-- management summary -->
<div id="paid_receipt" class="management">
    <hr>
    <div class="daily_monthly">
        <!-- daily revenue summary -->
        <div class="daily_report allResults">
            <h3 style="background:var(--otherColor)">Upcoming Payments</h3>
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

</div>