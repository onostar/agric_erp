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
<h2>Summary of Productions between '<?php echo date("jS M, Y", strtotime($from)) . "' and '" . date("jS M, Y", strtotime($to))?>'</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRevenue" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Production report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Product</td>
                <td>Total Qty</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_dateGro1con('production', 'date(post_date)', $from, $to, 'product_status', 1, 'product');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--otherColor)">
                    <?php
                        //get product name
                        $get_product = new selects();
                        $prd = $get_product->fetch_details_group('items', 'item_name', 'item_id', $detail->product);
                        echo strtoupper($prd->item_name);
                    ?>
                </td>
                <td style="color:var(--moreColor)">
                    <?php
                        //get sum
                        $get_prod_sum = new selects();
                        $prd_sums = $get_prod_sum->fetch_sum_2dateCond('production', 'product_qty', 'product', 'date(post_date)', $from, $to, $detail->product);
                        foreach($prd_sums as $prds){
                            echo $prds->total;
                        }
                        
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