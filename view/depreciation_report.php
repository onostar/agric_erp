<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    $year = date("Y");

?>

<div id="debt_paymentReport" class="displays management" style="width:100%!important">
        <div class="change_dashboard" style="margin:0 50px">
            <!-- check other stores dashboard -->
            <!-- <form method="POST"> -->
            <section>
                <label for="">Filter by Year</label><br>
                <select name="dep_year" id="dep_year" required onchange="getDepreciationYear(this.value)">
                    <option value="">Select Year</option>
                    <!-- get stores -->
                    <?php
                        $get_month = new selects();
                        $strs = $get_month->fetch_details_groupBy('depreciation', 'YEAR(post_date)');
                        foreach($strs as $str){
                    ?>
                    <option value="<?php echo $str->post_date?>"><?php echo date("Y", strtotime($str->post_date))?></option>
                    <?php }?>
                </select>
            </section>
        </div>
    <!-- <div class="select_date">
      
        <section>    
            <div class="from_to_date">
                <label>Select From Date</label><br>
                <input type="date" name="from_date" id="from_date"><br>
            </div>
            <div class="from_to_date">
                <label>Select to Date</label><br>
                <input type="date" name="to_date" id="to_date"><br>
            </div>
            <button type="submit" name="search_date" id="search_date" onclick="search('search_depreciation.php')">Search <i class="fas fa-search"></i></button>
        </section>
    </div> -->
<div class="displays allResults new_data" id="revenue_report">
    <h2>Annual Depreciation Report for <?php echo date("Y")?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Depreciation report')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--primaryColor)">
                <td>S/N</td>
                <td>Asset No.</td>
                <td>Asset Name</td>
                <td>Purchased</td>
                <td>Cost</td>
                <td>Qty</td>
                <td>Useful Life</td>
                <td>Accum. Depre</td>
                <td>Annual. Depre</td>
                <td>Book Value</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_users = new selects();
                $details = $get_users->fetch_details_yearlyGroup('depreciation', 'post_date', 'asset');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    $get_asset = new selects();
                    $rows = $get_asset->fetch_details_cond('assets', 'asset_id', $detail->asset);
                    foreach($rows as $row){
                        $asset_name = $row->asset;
                        $asset_no = $row->asset_no;
                        $location = $row->location;
                        $supplier = $row->supplier;
                        $cost = $row->cost;
                        $accum_dep = $row->accum_dep;
                        $useful_lfe = $row->useful_life;
                        $salvage = $row->salvage_value;
                        $value = $row->book_value;
                        $spec = $row->specification;
                        $ledger = $row->ledger;
                        $purchased = $row->purchase_date;
                        $deployed = $row->deployment_date;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $asset_no?></td>
                <td><?php echo $asset_name?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($purchased))?></td>
                <td style="color:red;"><?php echo "₦".number_format($detail->cost, 2)?></td>
                <td style="color:green; text-align:center"><?php echo $detail->quantity?></td>
                <td style="text-align:center; color:green"><?php echo $useful_lfe?></td>
               <td><?php echo "₦".number_format($accum_dep, 2)?></td>
                <td><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td style="color:green"><?php echo "₦".number_format($detail->book_value, 2)?></td>
              
                
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