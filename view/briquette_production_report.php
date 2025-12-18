<?php
     session_start();
     $store = $_SESSION['store_id'];
     include "../classes/dbh.php";
     include "../classes/select.php";
     //get store name
     $get_store = new selects();
     $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
     $store_name = $strs->store;

?>
<div id="production_report" class="displays management" style="width:100%!important;">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_briquette_production.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
    <div class="displays allResults new_data" id="revenue_report">
        <h2>Today's Briquette Production from <?php echo $store_name?></h2>
        <hr>
        <div class="search">
            <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
            <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Production report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
        </div>
        <table id="data_table" class="searchTable">
            <thead>
                <tr style="background:var(--tertiaryColor)">
                    <td>S/N</td>
                    <td>Production No.</td>
                    <td>Briquette (kg)</td>
                    <td>Leaves Used (kg)</td>
                    <td>Crown Used (kg)</td>
                    <td>Peels Used (kg)</td>
                    <td>Total Cost</td>
                    <td>Post Time</td>
                    <td>Posted by</td>
                    
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_curdateCon('briquette_production', 'date(date_produced)', 'store', $store);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:var(--otherColor)"><?php echo $detail->production_num?></td>
                    
                    <td style="color:green">
                        <?php
                            echo number_format($detail->briquette, 2)
                        ?>
                    </td>
                   
                    <td style="color:var(--otherColor); text-align:center">
                        <?php 
                           echo number_format($detail->leaves, 2)
                        ?>
                    </td>
                    <td style="color:var(--otherColor); text-align:center">
                        <?php 
                           echo number_format($detail->pineapple_crown, 2)
                        ?>
                    </td>
                    <td style="color:var(--otherColor); text-align:center">
                        <?php 
                           echo number_format($detail->pineapple_peel, 2)
                        ?>
                    </td>
                   
                    <td>
                        <?php
                            $total_cost = $detail->total_leave_cost + $detail->total_crown_cost + $detail->total_peel_cost;
                            echo "₦".number_format($total_cost, 2);
                        ?>
                    </td>
                    <td style="color:var(--moreColor)"><?php echo date("H:ia", strtotime($detail->date_produced));?></td>
                    <td>
                        <?php
                            //get posted by
                            $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                            echo $checkedin_by->full_name;
                        ?>
                    </td>
                    
                </tr>
                <?php $n++; endforeach;}?>
            </tbody>
        </table>
        <?php
            if(gettype($details) == "string"){
                echo "<p class='no_result'>'$details'</p>";
            }
            //total raw material
            $prds = $get_users->fetch_sum_curdateCon('briquette_production', 'total_leave_cost', 'date_produced', 'store', $store);
            foreach($prds as $prd){
                $leave_cost = $prd->total;

            }
            //total pineapple crown
            $crws =   $prds = $get_users->fetch_sum_curdateCon('briquette_production', 'total_crown_cost', 'date_produced', 'store', $store);
            foreach($crws as $crw){
                $crown_value = $crw->total;
            }
            //total pineapple peels
            $crws =   $prds = $get_users->fetch_sum_curdateCon('briquette_production', 'total_peel_cost', 'date_produced', 'store', $store);
            foreach($crws as $crw){
                $peel_value = $crw->total;
            }
            $total_production = $leave_cost + $peel_value + $crown_value;
            echo "<p class='total_amount' style='color:green; text-align:center;'>Total cost: ₦".number_format($total_production, 2)."</p>";
            
        ?>
        

    </div>
</div>
<script src="../jquery.js"></script>
<script src="../script.js"></script>