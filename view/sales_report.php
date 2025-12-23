<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<style>
    table td{
        font-size:.7rem!important;
        /* padding:2px!important; */
    }
    
</style>
<div id="revenueReport" class="displays management" style="margin:0!important;width:100%!important">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_sales_report.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
<div class="displays allResults new_data" id="revenue_report">
    <h2>Sales Report for today</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Sales report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Invoice</td>
                <td>Customer</td>
                <td>Item</td>
		        <td>Qty</td>
                <td>Unit Price</td>
                <td>Total Amount</td>
                <td>Discount</td>
                <td>Total Disc.</td>
                <!-- <td>Payment Mode</td> -->
                <td>Post Time</td>
                <td>Posted by</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_date2Cond('sales', 'date(post_date)', 'sales_status', 2, 'store', $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                     $names = $get_users->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        $item_name =  $names->item_name;
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><a style="color:green" href="javascript:void(0)" title="View invoice details"><?php echo $detail->invoice?></a></td>
                <td>
                    <?php
                        $cust = $get_users->fetch_details_group("customers", 'customer', 'customer_id', $detail->customer);
                        echo $cust->customer;
                    ?>
                </td>
                <td>
		            <?php
                       echo $item_name;
                    ?>
		        </td>
                <?php if($item_name == "CONCENTRATE"){?>
		        <td style="color:green; text-align:center"><?php echo $detail->quantity?> ltr</td>
                <?php }else{?>
		        <td style="color:green; text-align:center"><?php echo $detail->quantity?> kg</td>
                <?php }?>
                <td style="color:var(--otherColor)">
                    <?php echo "₦".number_format($detail->price, 2);?>
                </td>
                <td style="color:var(--secondaryColor)">
                    <?php 
                        echo "₦".number_format($detail->total_amount, 2)
                    ?>
                </td>
                <?php if($detail->discount > 0){?>
                <td style='color:red'><?php echo "-".number_format($detail->discount, 2);?></td>
                <?php }elseif($detail->discount < 0){?>
                <td style='color:green'><?php echo "+".number_format(-($detail->discount), 2);?></td>
                <?php }else{?>
                    <td style='color:green'><?php echo number_format($detail->discount, 2);?></td>
                <?php }?>
                <td>
                    <?php 
                        $total_disc = $detail->discount * $detail->quantity;
                        if($total_disc > 0){
                            echo "<span style='color:red'>-".number_format($total_disc, 2)."</span>";
                        }elseif($total_disc < 0){
                            echo "<span style='color:green'>+".number_format(-($total_disc), 2)."</span>";
                        }else{
                            echo "<span style='color:green'>".number_format($total_disc, 2)."</span>";
                        }
                    
                    ?>
                    
                </td>
                <!-- <td style="color:red">
                    <?php echo "₦".number_format($detail->discount, 2);?>
                </td> -->
                <td style="display:none">
                    <?php
                            //get payment mode
                           /*  $get_mode = new selects();
                            $mode = $get_mode->fetch_details_group('payments', 'payment_mode', 'invoice', $detail->invoice);
                            //check if invoice is more than 1
                            $get_mode_count = new selects();
                            $rows = $get_mode_count->fetch_count_cond('payments', 'invoice', $detail->invoice);
                                if($rows > 1){
                                    echo "Multiple payment";
                                }else{
                                    echo $mode->payment_mode;

                                } */
                        ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("H:i:sa", strtotime($detail->post_date));?></td>
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
    ?>
        <!-- <div class="all_amounts"> -->
            <div class="all_modes">
    <?php
        //get cash
        $cashs = $get_users->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'Cash', 'store', $store);
        if(gettype($cashs) === "array"){
            foreach($cashs as $cash){
            ?>
                <p class="sum_amount" onclick="showPage('cash_list.php')"style="background:var(--otherColor)"><strong>Cash</strong>: ₦ <?php echo number_format($cash->total, 2)?></p>

            <?php
            }
        }
        //get pos
        $poss = $get_users->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'POS', 'store', $store);
        if(gettype($poss) === "array"){
            foreach($poss as $pos){
                ?>
                <p class="sum_amount" onclick="showPage('pos_list.php')"style="background:var(--secondaryColor)"><strong>POS</strong>: ₦ <?php echo number_format($pos->total, 2)?></p>

            <?php
            }
        }
        //get transfer
        $trfs = $get_users->fetch_sum_curdate2Con('payments', 'amount_paid', 'post_date', 'payment_mode', 'Transfer', 'store', $store);
        if(gettype($trfs) === "array"){
            foreach($trfs as $trf){
                ?>
                <p class="sum_amount" onclick="showPage('transfer_list.php')"style="background:var(--primaryColor)"><strong>Transfer</strong>: ₦ <?php echo number_format($trf->total, 2)?></p>

            <?php
            }
        }
        //get credit
        $trfs = $get_users->fetch_sum_curdate2Con('payments', 'amount_due', 'post_date', 'payment_mode', 'Credit', 'store', $store);
        if(gettype($trfs) === "array"){
            foreach($trfs as $trf){
                ?>
                <p class="sum_amount" onclick="showPage('credit_sales_list.php')"style="background:brown"><strong>Credit</strong>: ₦ <?php echo number_format($trf->total, 2)?></p>

            <?php
            }
        }
        ?>
            <!-- </div> -->
            <!-- <div class="all_total"> -->
        <?php
        // get sum
        $amounts = $get_users->fetch_sum_curdate2Con('sales', 'total_amount', 'date(post_date)', 'store', $store, 'sales_status', 2);
        foreach($amounts as $amount){
            $paid_amount = $amount->total;
            
        }
        //if credit was sold
       /*  $get_credit = new selects();
        $credits = $get_credit->fetch_sum_curdate2Con('payments', 'amount_due', 'post_date', 'payment_mode', 'Credit', 'store', $store);
        if(gettype($credits) === "array"){
            foreach($credits as $credit){
                $owed_amount = $credit->total;
            }
            $total_revenue = $owed_amount + $paid_amount;
            echo "<p class='sum_amount' style='background:green; margin-left:250px; font-size:1rem;'><strong>Total</strong>: ₦".number_format($total_revenue, 2)."</p>";

        } */
        //if no credit sales
        // if(gettype($credits) == "string"){
            echo "<p class='sum_amount' style='background:green; margin-left:100px;'><strong>Total</strong>: ₦".number_format($paid_amount, 2)."</p>";
            
        // }
        
    ?>
    <!-- <a href="javascript:void(0)" title="print end of day summary" class="sum_amount" style="background:#c4c4c4; color:#000" onclick="printEndOfDay()">Print Summary <i class="fas fa-print"></i></a> -->
            <!-- </div> -->
        </div>
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>