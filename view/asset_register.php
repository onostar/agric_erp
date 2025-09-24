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
    <h2>Asset Register</h2>
    <hr>
    <div class="search">
        <input type="search" id="searchRoom" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('item_list_table', 'Asset Register')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="item_list_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Post Date</td>
                <td>Supplier.</td>
                <td>Asset No.</td>
                <td>Asset Name</td>
                <td>Cost</td>
                <td>Qty</td>
                <!-- <td>Accum. Depre.</td>
                <td>Book Value</td> -->
                <td>Purchase Date</td>
                <!-- <td>Deploy Date</td> -->
                <!-- <td>Specification</td> -->
                 
                <!-- <td>Location</td> -->
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_negCond1('assets', 'asset_status', -1);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:green">
                    <?php echo date("d-M-Y", strtotime($detail->post_date))?>
                </td>
                <td><?php echo $detail->supplier?></td>
                <td><?php echo $detail->asset_no?></td>
                <td><?php echo $detail->asset?></td>
                <td style="color:red;"><?php echo "₦".number_format($detail->cost, 2)?></td>
                <td style="color:green; text-align:center"><?php echo $detail->quantity?></td>
               <!--  <td><?php echo "₦".number_format($detail->accum_dep, 2)?></td>
                <td style="color:green;"><?php echo "₦".number_format($detail->book_value, 2)?></td> -->
                <td style="color:var(--moreColor)"><?php echo date("d-M-Y", strtotime($detail->purchase_date))?></td>
               <!--  <td style="color:var(--moreColor)"><?php echo date("d-m-Y", strtotime($detail->deployment_date))?></td> -->
                <!-- <td><?php echo $detail->specification?></td> -->
                <!-- <td>
                    <?php
                        //get location
                        $get_cat_name = new selects();
                        $cat_name = $get_cat_name->fetch_details_group('asset_locations', 'location', 'location_id', $detail->location);
                        echo $cat_name->location;
                    ?>
                </td> -->
                <td>
                    <a style="color:#fff;background:var(--otherColor); padding:5px; border-radius:10px" href="javascript:void(0)" title="View details" onclick="showPage('asset_details.php?asset=<?php echo $detail->asset_id?>')">View <i class="fas fa-eye"></i></a>
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