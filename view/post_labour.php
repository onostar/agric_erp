

<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="post_purchase" class="displays management" style="width:100%!important">
<div class="displays allResults new_data" id="revenue_report">
    <h2>Post Pending Labour Cost</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Labour Cost report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
        <!-- <a href="javascrit:void(0)" onclick="showPage('post_other_trx.php')" style="background:brown; color:#fff; padding:8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;">Close <i class="fas fa-close"></i></a> -->
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>field</td>
                <td>Type</td>
                <td>title</td>
                <td>Details</td>
                <td>Labour Cost</td>
                <td>Date</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2cond('tasks', 'payment_status', 'farm', 0, $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                
                <td>
                    <?php
                        //get field
                        $rows = $get_users->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo $rows->field_name;
                    ?>
                </td>
                <td><?php echo $detail->task_type?></td>
                <td>
                    <?php 
                        echo $detail->title;
                    ?>
                </td>
                <td>
                    <?php 
                        echo $detail->description;
                    ?>
                </td>
                
                <td style="color:red">
                    <?php 
                        echo "₦".number_format($detail->labour_cost, 2);
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y, H:ia", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $checkedin_by = $get_users->fetch_details_group('users', 'full_name', 'user_id', $detail->done_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:#fff;background:var(--otherColor); padding:5px; border-radius:10px" href="javascript:void(0)" title="post payments" onclick="showPage('task_payments.php?task=<?php echo $detail->task_id?>')">Post <i class="fas fa-hand-holding-dollar"></i></a>
                </td>
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(is_array($details)){
            // get sum
            $get_total = new selects();
            $amounts = $get_total->fetch_sum_double('tasks', 'labour_cost', 'payment_status', 0, 'farm', $store);
            foreach($amounts as $amount){
                $paid_amount = $amount->total;
                
            }
            
            echo "<p class='sum_amount' style='margin-left:100px; color:green;text-decoration:underline; text-align:right; font-size:1rem; font-weight:bold'><strong>Total</strong>: ₦".number_format($paid_amount, 2)."</p>";
        }else{
            echo "<p class='no_result'>'$details'</p>";
        }
    ?>
       
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>