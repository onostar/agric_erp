<?php
    session_start();
    $year = htmlspecialchars(stripslashes($_POST['dep_year']));
    include "../classes/dbh.php";
    include "../classes/select.php";
    $full_date = date("Y-m-d", strtotime($year));
    // echo $full_date;

?>
    <h2>Depreciation Report as at <?php echo date("Y", strtotime($year))?></h2>
    <hr>
    
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--otherColor)">
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
                $details = $get_users->fetch_details_specYearGro('depreciation', $full_date, 'asset');
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
                        $quantity = $row->quantity;
                    }
                    
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $asset_no?></td>
                <td><?php echo $asset_name?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($purchased))?></td>
                <td style="color:red;"><?php echo "₦".number_format($detail->cost, 2)?></td>
                <td style="color:green;text-align:center"><?php echo $quantity?></td>
                <td style="text-align:center; color:green"><?php echo $useful_lfe?></td>
               <td><?php echo "₦".number_format($detail->accum_dep, 2)?></td>
                <td><?php echo "₦".number_format($detail->amount, 2)?></td>
                <td style="color:green"><?php echo "₦".number_format($detail->book_value, 2)?></td>
              
                
            </tr>
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php 
       
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$detail'</p>";
        }
    ?>
</div>