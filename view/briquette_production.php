<div id="produce" class="displays">

<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        $user_id = $_SESSION['user_id'];

        //generate product number
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "PR".$store.$todays_date.$ran_num.$user_id;
        // $_SESSION['invoice'] = $invoice;
    ?>
<style>
    .section-divider {
        display: flex;
        align-items: center;
        margin: 1rem 0;
        font-weight: 600;
        color: #313131ff;
        font-size: 14px;
        text-transform: uppercase;
    }

    .section-divider::before,
    .section-divider::after {
        content: "";
        flex: 1;
        height: 1px;
        background: #ddd;
    }

    .section-divider span {
        padding: 0 12px;
        background: #fff;
    }
    label{
        font-size:.75rem;
    }
</style>
    <div class="add_user_form" style="width:40%; margin:10px 0;">
        <h3 style="background:var(--tertiaryColor); color:#fff; text-align:left!important;">Briquette Production</h3>
        <?php
            //get cost prices of inputs and outputs
            //LEAVES cost price
            $get_prices = new selects();
            $leaves = $get_prices->fetch_details_group('items', 'item_id','item_name', 'LEAVES');
            $leaves_id = $leaves->item_id;
            $leaves_cost = $get_prices->fetch_details_2cond('prices', 'item', 'store', $leaves_id, $store);
            if(is_array($leaves_cost)){
                foreach($leaves_cost as $lc){
                    $leaves_cost_price = $lc->cost;
                }
            }else{
                $leaves_cost_price = 0;
            }
            //get leaves previous qty
            $prev_leaves_qty = $get_prices->fetch_details_2cond('inventory', 'item', 'store', $leaves_id, $store);
            if(is_array($prev_leaves_qty)){
                foreach($prev_leaves_qty as $plq){
                    $prev_leaves_quantity = $plq->quantity;
                }
            }else{
                $prev_leaves_quantity = 0;
            }
            
            //pineapple peel cost price and revous qty
            $ppr = $get_prices->fetch_details_group('items', 'item_id', 'item_name', 'PINEAPPLE PEEL');
            $pprecs = $get_prices->fetch_details_2cond('prices', 'item', 'store', $ppr->item_id, $store);
            if(is_array($pprecs)){
                foreach($pprecs as $pprec){
                    $pineapple_peel_cost = $pprec->cost;
                }
            }else{
                $pineapple_peel_cost = 0;
            }
            //get pineapple peel previous qty
            $prev_pineapple_peel_qty = $get_prices->fetch_details_2cond('inventory', 'item', 'store', $ppr->item_id, $store);
            if(is_array($prev_pineapple_peel_qty)){
                foreach($prev_pineapple_peel_qty as $ppq){
                    $prev_pineapple_peel_quantity = $ppq->quantity;
                }
            }else{
                $prev_pineapple_peel_quantity = 0;
            }
            //pineapple crown cost price and revous qty
            $pcr = $get_prices->fetch_details_group('items', 'item_id', 'item_name', 'PINEAPPLE CROWN');
            $pcrecs = $get_prices->fetch_details_2cond('prices', 'item', 'store', $pcr->item_id, $store);
            if(is_array($pcrecs)){
                foreach($pcrecs as $prec){
                    $pineapple_crown_cost = $prec->cost;
                }
            }else{
                $pineapple_crown_cost = 0;
            }
            //get pineapple crown previous qty
            $prev_pineapple_crown_qty = $get_prices->fetch_details_2cond('inventory', 'item', 'store', $pcr->item_id, $store);
            if(is_array($prev_pineapple_crown_qty)){
                foreach($prev_pineapple_crown_qty as $pcq){
                    $prev_pineapple_crown_quantity = $pcq->quantity;
                }
            }else{
                $prev_pineapple_crown_quantity = 0;
            }
        ?>
        <form class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <input type="hidden" name="product_num" id="product_num" value="<?php echo $invoice?>">
                <input type="hidden" id="leave_prev_qty" name="leave_prev_qty" value="<?php echo $prev_leaves_quantity?>">
                <input type="hidden" id="crown_prev_qty" name="crown_prev_qty" value="<?php echo $prev_pineapple_crown_quantity?>">
                <input type="hidden" id="peel_prev_qty" name="peel_prev_qty" value="<?php echo $prev_pineapple_peel_quantity?>">
                <input type="hidden" id="leave_cost" name="leave_cost" value="<?php echo $leaves_cost_price?>">
                <input type="hidden" id="pineapple_crown_cost" name="pineapple_crown_cost" value="<?php echo $pineapple_crown_cost?>">
                <input type="hidden" id="pineapple_peel_cost" name="pineapple_peel_cost" value="<?php echo $pineapple_peel_cost?>">
                <div class="data" style="width:100%; margin:0;">
                    <label for="briquette">Briquette Produced (KG)</label>
                    <input type="number" name="briquette" id="briquette" value="0">
                </div>
            </div>
            <div class="section-divider">
                <span>Production Inputs</span>
            </div>
            <div class="inputs" style="justify-content:left;align-items:flex-start; gap:.5rem">
                <div class="data" style="width:100%; margin:0;">
                    <label for="leaves">Leaves (kg) - Available Qty => <?php echo number_format($prev_leaves_quantity, 2)?>kg</label>
                    <input type="number" name="leaves" id="leaves" value="0">
                </div>
                <div class="data" style="width:100%; margin:0;">
                    <label for="pineapple_peel">Pineapple Peel (kg) - Available Qty => <?php echo number_format($prev_pineapple_peel_quantity, 2)?>kg</label>
                    <input type="number" name="pineapple_peel" id="pineapple_peel" value="0">
                </div>
                <div class="data" style="width:100%; margin:0;">
                    <label for="pineapple_crown">Pineapple Crown (kg) - Available Qty => <?php echo number_format($prev_pineapple_crown_quantity, 2)?>kg</label>
                    <input type="number" name="pineapple_crown" id="pineapple_crown" value="0">
                </div>
                
                <div class="data" style="width:100%">
                    <button type="button"  onclick="makeBriquette()">Save Production</button>
                </div>
            </div>
        </form>
    </div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>
</div>
