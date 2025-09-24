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
<div id="production_report" class="displays management" style="width:70%!important; margin:0 20px!Important">
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
            <button type="submit" name="search_date" id="search_date" onclick="search('search_production_statistics.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div>
    <div class="displays allResults new_data" id="revenue_report">
        <h2>Summary of Today's Production</h2>
        <hr>
        <div class="search">
            <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
            <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Production statistics')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
        </div>
        <table id="data_table" class="searchTable">
            <thead>
                <tr style="background:var(--tertiaryColor)">
                    <td>S/N</td>
                    <td>Product</td>
                    <td>Total Qty</td>
                    <!-- <td>Raw materials</td> -->
                    
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_users = new selects();
                    $details = $get_users->fetch_details_curdateGro1con('production', 'date(post_date)', 'product_status', 1, 'product');
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
                            $prd_sums = $get_prod_sum->fetch_sum_curdateCon('production', 'product_qty', 'date(post_date)', 'product', $detail->product);
                            foreach($prd_sums as $prds){
                                echo $prds->total;
                            }
                           
                        ?>
                    </td>
                    <!-- <td style="color:green; text-align:Center">
                        <?php 
                            //get total items with that invoice
                            $get_sum = new selects();
                            $sums = $get_sum->fetch_count_cond('production', 'product_number', $detail->product_number);
                            echo $sums;
                        ?>
                    </td> -->
                    
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
<script src="../jquery.js"></script>
<script src="../script.js"></script>