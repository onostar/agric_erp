<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";


?>
<div id="pendingProduction" class="displays management" style="width:70%!important;margin:10px 20px!important">
<div class="displays allResults new_data" id="revenue_report">
    <h2>Pending Productions</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Pending production')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Production No.</td>
                <td>Product</td>
                <td>Raw materials</td>
                <td>Post Date</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2condGroup('production', 'store', 'product_status', $store, 0, 'product_number');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)"><?php echo $detail->product_number?></td>
                
                <td style="color:var(--otherColor)">
                    <?php
                        //get product name
                        $get_product = new selects();
                        $prd = $get_product->fetch_details_group('items', 'item_name', 'item_id', $detail->product);
                        echo strtoupper($prd->item_name);
                    ?>
                </td>
                <td style="color:green; text-align:Center">
                    <?php 
                        //get total items with that invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_count_cond('production', 'product_number', $detail->product_number);
                        echo $sums;
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:#fff; background:var(--otherColor); padding:5px; border-radius:10px; color:#fff" href="javascript:void(0)" title="View details" onclick="showPage('complete_production.php?invoice=<?php echo $detail->product_number?>')"> <i class="fas fa-eye"></i> View</a>
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