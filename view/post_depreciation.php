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
        <h3 style="background:var(--moreColor); text-align:left">Post Yearly Depreciation</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm" style="padding:10px 0">
            <div class="inputs" style="gap:.7rem; align-items:flex-start">
                <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                <div class="data" style="width:30%;">
                    <label for="exp_date">Start Date</label>
                    <input type="date" name="exp_date" id="exp_date" required>
                </div>
                
                <div class="data" style="width:30%;">
                    <label for="exp_head">Asset</label>
                    <select name="asset" id="asset">
                        <option value="" selected>Select Asset</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_cond('assets', 'asset_status', 2);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->asset_id?>"><?php echo $head->asset?></option>
                        <?php }?>
                    </select>
                </div>
                <!-- </div><div class="data" style="width:30%;">
                    <label for="amount">Transaction Amount</label>
                    <input type="text" name="amount" id="amount" required placeholder="5000">
                </div> -->
                <div class="data" style="width:30%;">
                    <label for="exp_head"><span class="ledger">Dr. </span>Expense Ledger</label>
                    <select name="dr_ledger" id="dr_ledger">
                        <option value="" selected>Select ledger</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_cond('ledgers', 'class', 15);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" style="width:35%;">
                    <label for="contra"><span class="ledger">Cr. </span>Contra Ledger</label>
                    <select name="contra" id="contra">
                        <option value="" selected>Select Asset ledger</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_cond('ledgers', 'class', 6);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                        <?php }?>
                    </select>
                </div>
                
                <div class="data" style="width:60%;">
                    <label for="details"> Description</label>
                    <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction">Asset Depreciation</textarea>
                </div>
                <div class="data" style="width:30%; margin:5px 0">
                    <button type="submit" id="post_exp" name="post_exp" onclick="postDepreciation()">Post Depreciation <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
