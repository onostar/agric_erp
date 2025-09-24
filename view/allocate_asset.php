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
    <h2>Allocate or Re-assign Asset</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Asset Register')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Asset Name</td>
                <td>Asset No.</td>
                <td>Asset Cost</td>
                <td>Purchase Date</td>
                <td>Location</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_negCond1('assets', 'asset_status', 1);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td><?php echo $detail->asset?></td>
                <td><?php echo $detail->asset_no?></td>
                <td style="color:green;"><?php echo "₦".number_format($detail->cost, 2)?></td>
                <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->purchase_date))?></td>
                
                <td>
                    <?php
                        //get location
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('asset_locations', 'location', 'location_id', $detail->location);
                        echo $cat_name->location;
                    ?>
                </td>
                <td class="prices">
                    <a style="background:var(--otherColor)!important; color:#fff!important; padding:5px 8px; border-radius:15px;" href="javascript:void(0)" onclick="getForm('<?php echo $detail->asset_id?>', 'get_asset_allocation.php');"><i class="fas fa-pen"></i></a>
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
<?php }?>

</div>