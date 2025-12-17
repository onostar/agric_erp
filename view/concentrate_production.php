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

</style>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--tertiaryColor); color:#fff; text-align:left!important;">Concentrate Production</h3>
        <?php
            //get cost prices of inputs and outputs
            //pineapple cost price
            $get_prices = new selects();
            $pincs = $get_prices->fetch_details_cond('items', 'item_name', 'PINEAPPLE');
            if(is_array($pincs)){
                foreach($pincs as $pinc){
                    $pineapple_cost = $pinc->cost_price;
                    $pineapple_id = $pinc->item_id;
                }
            }else{
                $pineapple_cost = 0;
            }
            //get pineapple previous qty
            $pine_qtys = $get_prices->fetch_details_2cond('inventory', 'item', 'store', $pineapple_id, $store);
            if(is_array($pine_qtys)){
                foreach($pine_qtys as $pine_qty){
                    $prev_qty = $pine_qty->quantity;
                }
            }else{
                $prev_qty = 0;
            }
            //pineapple peel recovery value
            $pprecs = $get_prices->fetch_details_cond('items', 'item_name', 'PINEAPPLE PEEL');
            if(is_array($pprecs)){
                foreach($pprecs as $pprec){
                    $pineapple_peel_value = $pprec->sales_price;
                }
            }else{
                $pineapple_peel_value = 0;
            }
            //pineapple crown recovery value
            $pcrecs = $get_prices->fetch_details_cond('items', 'item_name', 'PINEAPPLE CROWN');
            if(is_array($pcrecs)){
                foreach($pcrecs as $prec){
                    $pineapple_crown_value = $prec->sales_price;
                }
            }else{
                $pineapple_crown_value = 0;
            }
        ?>
        <form class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <input type="hidden" name="product_num" id="product_num" value="<?php echo $invoice?>">
                <input type="hidden" id="prev_qty" name="prev_qty" value="<?php echo $prev_qty?>">
                <input type="hidden" id="pineapple_cost" name="pineapple_cost" value="<?php echo $pineapple_cost?>">
                <input type="hidden" id="pineapple_crown_value" name="pineapple_crown_value" value="<?php echo $pineapple_crown_value?>">
                <input type="hidden" id="pineapple_peel_value" name="pineapple_peel_value" value="<?php echo $pineapple_peel_value?>">
                <div class="data" style="width:100%; margin:0;">
                    <label for="pineapple">Pineapple Input (KG) - Available Qty => <?php echo number_format($prev_qty, 2)?>kg</label>
                    <input type="number" name="pineapple" id="pineapple" value="0">
                </div>
            </div>
            <div class="section-divider">
                <span>Production Outputs</span>
            </div>
            <div class="inputs" style="justify-content:left;align-items:flex-start; gap:.5rem">
                <div class="data" style="width:49%; margin:0;">
                    <label for="concentrate">Concentrates (Litres)</label>
                    <input type="number" name="concentrate" id="concentrate" value="0">
                </div>
                <div class="data" style="width:49%; margin:0;">
                    <label for="pineapple_peel">Pineapple Peel (kg)</label>
                    <input type="number" name="pineapple_peel" id="pineapple_peel" value="0">
                </div>
                <div class="data" style="width:49%; margin:0;">
                    <label for="pineapple_peel">Pineapple Crown (kg)</label>
                    <input type="number" name="pineapple_crown" id="pineapple_crown" value="0">
                </div>
                <div class="data" style="width:49%; margin:0;">
                    <label for="others">Others (kg)</label>
                    <input type="number" name="others" id="others" value="0">
                </div>
                <div class="data" style="width:100%">
                    <button type="button"  onclick="makeConcentrate()">Save Production</button>
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
