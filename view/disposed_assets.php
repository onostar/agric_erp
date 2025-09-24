<div id="allocate_asset">
<?php
session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    

    //get user
    if(isset($_SESSION['user'])){
        $username = $_SESSION['user'];
        //get user role
        $get_role = new selects();
        $roles = $get_role->fetch_details_group('users', 'user_role', 'username', $username);
        $role = $roles->user_role;

?>
    <div class="info"></div>
<div class="displays allResults" id="bar_items">
    <h2>List of disposed assets</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Disposed Assets')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--tertiaryColor)">
                <td>S/N</td>
                <td>Asset Name</td>
                <td>Asset No.</td>
                <td>Unit Cost</td>
                <td>Qty</td>
                <td>Purchase Date</td>
                <td>Accum Depre</td>
                <td>Total Depre</td>
                <td>Reason</td>
                <td>Amount</td>
                <td>Dispose date</td>
                <!-- <td></td> -->
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details('disposed_assets');
                if(gettype($details) === 'array'){
                foreach($details as $detail):
                    //get asset details
                    $get_asset = new selects();
                    $rows = $get_asset->fetch_details_cond('assets', 'asset_id', $detail->asset);
                    foreach($rows as $row){
                        $asset = $row->asset;
                        $asset_no = $row->asset_no;
                        $purchased = $row->purchase_date;
                        $cost = $row->cost;
                        $supplier = $row->supplier;
                        $location = $row->location;
                    }
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $asset?></td>
                <td><?php echo $asset_no?></td>
                <td style="color:red;"><?php echo "₦".number_format($cost, 2)?></td>
                <td style="color:var(--otherColor); text-align:center"><?php echo $detail->quantity?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($purchased))?></td>
                <td><?php echo number_format($detail->accum_dep, 2)?></td>
                <td style="color:green"><?php echo number_format($detail->accum_dep * $detail->quantity, 2)?></td>
                <!-- <td>
                    <?php
                        //get location
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('asset_locations', 'location', 'location_id', $location);
                        echo $cat_name->location;
                    ?>
                </td> -->
                <td><?php echo $detail->reason?></td>
                <td style="color:red"><?php echo number_format($detail->amount, 2)?></td>
                <td style="color:var(--otherColor)"><?php echo date("d-m-Y", strtotime($detail->disposed_date))?></td>
                
                
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
<?php }?>

</div>