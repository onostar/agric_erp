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
<h2>Harvest report for <?php echo $store_name?> between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Harvest report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Field</td>
                <td>Product</td>
                <td>Qty(kg)</td>
                <td>Unit Cost</td>
                <td>Total Cost</td>
                <td>Post Date</td>
                <td>Posted by</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_2dateCon('harvests', 'farm', 'date(post_date)', $from, $to, $store);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)">
                    <?php
                        //get field name
                        $fds = $get_users->fetch_details_group('fields', 'field_name', 'field_id', $detail->field);
                        echo strtoupper($fds->field_name);
                    ?>
                </td>
                <td style="color:var(--otherColor)">
                    <?php
                        //get product name
                        $get_product = new selects();
                        $prd = $get_product->fetch_details_group('items', 'item_name', 'item_id', $detail->crop);
                        echo strtoupper($prd->item_name);
                    ?>
                </td>
                <td style="text-align:center"><?php echo $detail->quantity?></td>
                <td><?php echo "₦".number_format($detail->unit_cost, 2);?></td>
                <td style="color:red">
                    <?php
                        //get total cost
                        $costs = $get_users->fetch_sum_2colCond('harvests', 'quantity', 'unit_cost', 'harvest_id', $detail->harvest_id);
                        if(is_array($costs)){
                            foreach($costs as $cost){
                                echo "₦".number_format($cost->total, 2);
                            }
                        }else{
                            echo "₦0.00";
                        }
                    ?>
                </td>
                <td style="color:var(--moreColor)"><?php echo date("jS M, Y", strtotime($detail->post_date));?></td>
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
        $prds = $get_users->fetch_sum_2col2Date1Con('harvests', 'quantity', 'unit_cost', 'date(post_date)', $from, $to, 'farm', $store);
        if(gettype($prds) === 'array'){
            foreach($prds as $prd){
                echo "<p class='total_amount' style='color:green; text-align:center;'>Total cost: ₦".number_format($prd->total, 2)."</p>";
            }
        }
    ?>