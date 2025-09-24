<?php
    session_start();
    $store = $_SESSION['store_id'];
    $from = htmlspecialchars(stripslashes($_POST['from_date']));
    $to = htmlspecialchars(stripslashes($_POST['to_date']));

    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get store name
    $get_store = new selects();
    $strs = $get_store->fetch_details_group('stores', 'store', 'store_id', $store);
    $store_name = $strs->store;

    
?>
<h2>Production report for <?php echo $store_name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Production report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Production No.</td>
                <td>Product</td>
                <td>Qty(kg)</td>
                <td>Raw materials</td>
                <td>Cost</td>
                <td>Post Date</td>
                <td>Post Time</td>
                <td>Posted by</td>
                <td></td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_dateGro2con('production', 'date(post_date)', $from, $to, 'store', $store, 'product_status', 1, 'product_number');
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
                <td><?php echo $detail->product_qty?></td>
                <td style="color:green; text-align:Center">
                    <?php 
                        //get total items with that invoice
                        $get_sum = new selects();
                        $sums = $get_sum->fetch_count_cond('production', 'product_number', $detail->product_number);
                        echo $sums;
                    ?>
                </td>
                <td style="color:red">
                    <?php
                        //get total cost
                        $get_cost = new selects();
                        $costs = $get_cost->fetch_sum_2colCond('production', 'raw_quantity', 'unit_cost', 'product_number', $detail->product_number);
                        foreach($costs as $cost){
                            echo "₦".number_format($cost->total);
                        }
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
                <td style="color:var(--moreColor)"><?php echo date("H:ia", strtotime($detail->post_date));?></td>
                <td>
                    <?php
                        //get posted by
                        $get_posted_by = new selects();
                        $checkedin_by = $get_posted_by->fetch_details_group('users', 'full_name', 'user_id', $detail->posted_by);
                        echo $checkedin_by->full_name;
                    ?>
                </td>
                <td>
                    <a style="color:#fff; background:var(--otherColor); padding:5px; border-radius:10px; color:#fff" href="javascript:void(0)" title="View details" onclick="showPage('view_materials.php?invoice=<?php echo $detail->product_number?>')"> <i class="fas fa-eye"></i> View</a>
                </td>
                
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        $get_prds = new selects();
        $prds = $get_prds->fetch_sum_2col2Date1Con('production', 'raw_quantity', 'unit_cost', 'date(post_date)', $from, $to, 'store', $store);
        if(gettype($prds) === 'array'){
            foreach($prds as $prd){
                echo "<p class='total_amount' style='color:green; text-align:center;'>Total cost: ₦".number_format($prd->total, 2)."</p>";
            }
        }
    ?>