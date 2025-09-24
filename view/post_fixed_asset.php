<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }
?>
<div id="post_expense" class="displays">
    <div class="info" style="width:70%; margin:5px 0;"></div>
    <div class="more_buttons" style="width:70%">
        <!-- <button class="page_navs" id="back" onclick="showPage('post_other_trx.php')"><i class="fas fa-angle-double-left"></i> Back</button> -->
        <a href="javascript:void(0)" title="Add a ledger" onclick="showPage('account_ledger.php')"><i class="fas fa-receipt"></i> Add Ledger</a>
    </div>
    <div class="add_user_form" style="width:70%; margin:5px 0;">
        <h3 style="background:var(--tertiaryColor); text-align:left">Post Fixed Asset</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm" style="padding:10px 0">
            <div class="inputs" style="gap:.7rem; align-items:flex-start">
                <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                <div class="data" style="width:30%;">
                    <label for="exp_date">Date</label>
                    <input type="date" name="exp_date" id="exp_date" required value="<?php echo date("Y-m-d")?>">
                </div>
                <div class="data" style="width:30%;">
                    <label for="exp_head">Asset</label>
                    <select name="asset" id="asset" onchange="getAssetLedger(this.value)">
                        <option value="" selected>Select Asset</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_cond('assets', 'asset_status', 0);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->asset_id?>"><?php echo $head->asset?></option>
                        <?php }?>
                    </select>
                </div>
                <!-- <div class="data" style="width:30%;">
                    <label for="amount">Transaction Amount</label>
                    <input type="text" name="amount" id="amount" required placeholder="5000">
                </div> -->
                <div class="data" style="width:32%;">
                    <label for="exp_head"><span class="ledger">Dr. </span>Asset Ledger</label>
                    <select name="exp_head" id="exp_head">
                        <option value="" selected>Select ledger</option>
                       
                    </select>
                </div>
                <div class="data" style="width:35%;">
                    <label for="contra"><span class="ledger">Cr. </span>Contra Ledger</label>
                    <select name="contra" id="contra">
                        <option value="" selected>Select Contra ledger</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_eitherCon3('ledgers', 'class', 1, 2, 'account_group', 2);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                        <?php }?>
                    </select>
                </div>
                
                <div class="data" style="width:60%;">
                    <label for="details"> Description</label>
                    <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction">Payment for asset purchased</textarea>
                </div>
                <div class="data" style="width:30%; margin:5px 0">
                    <button type="submit" id="post_exp" name="post_exp" onclick="postAsset()">Post Asset <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
