<div id="post_purchase">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    
    if(isset($_GET['asset'])){
        $asset = $_GET['asset'];
        $get_asset = new selects();
        $rows = $get_asset->fetch_details_cond('assets', 'asset_id', $asset);
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
        //get location
        $get_cat_name = new selects();
        $cat_name = $get_cat_name->fetch_details_group('asset_locations', 'location', 'location_id', $location);
        $loc_name = $cat_name->location;
        //get ledger
        $get_ledg_name = new selects();
        $ledg_name = $get_ledg_name->fetch_details_group('ledgers', 'ledger', 'acn', $ledger);
        $account = $ledg_name->ledger;

?>


<div class="displays all_details" style="width:100%!important">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" style="margin:0 50px"onclick="showPage('asset_register.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="guest_name">
        <div class="displays allResults" id="payment_det">
            <h3 style="background:var(--otherColor); color:#fff; padding:10px;">SHOWING DETAILS FOR "<?php echo $asset_name?>"</h3>
            <p>Asset #: <?php echo $asset_no?></p>
            <div class="payment_detsss">
                
                <div class="pay_form" style="width:100%;">
                    
                    <div class="close_stockin add_user_form" style="width:100%; margin:0;" id="asset_det">
                        <section class="addUserForm">
                        <div class="inputs" style="display:flex; flex-wrap:wrap; gap:.5rem">
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Ledger</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo $account?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Supplier</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo $supplier?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Purchase Date</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo date("d-M-Y", strtotime($purchased))?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Purchase Cost</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo "₦".number_format($cost, 2)?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Quantity</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo $quantity?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Salvage Value</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo "₦".number_format($salvage, 2)?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Useful Life</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo $useful_lfe?> Year(s)" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Deployment Date</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo date("d-M-Y", strtotime($deployed))?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Accumulated Depreciation</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo "₦".number_format($accum_dep, 2)?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Book Value</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo "₦".number_format($value, 2)?>" readonly>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Specification</label><br>
                                <textarea><?php echo $spec?></textarea>
                            </div>
                            <div class="data" style="width:23%">
                            <label for="discount" style="color:red;">Location</label><br>
                                <input type="text" style="padding:10px;border-radius:5px;" value="<?php echo $loc_name?>" readonly>
                            </div>
                        </div>
                        <p style="color:red">Total Cost: 
                            <?php
                                echo "₦".number_format($quantity * $cost, 2);
                            ?>
                        </p>
                        <p style="color:green">Total Book Value: 
                            <?php
                                echo "₦".number_format($quantity * $value, 2);
                            ?>
                        </p>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    
</div>
<?php
            }
        
    }else{
        header("Location: ../index.php");
    }
?>
</div>