<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    }
    $opening_date = "2024-01-01";
?>
<div id="post_expense" class="displays">
<div class="info" style="width:70%; margin:5px 0;"></div>

    <div class="more_buttons" style="width:70%">
        <!-- <button class="page_navs" id="back" onclick="showPage('post_other_trx.php')"><i class="fas fa-angle-double-left"></i> Back</button> -->
        <a href="javascript:void(0)" title="Add a ledger" onclick="showPage('account_ledger.php')"><i class="fas fa-receipt"></i> Add Ledger</a>
    </div>
    
    <div class="add_user_form" style="width:70%; margin:5px 0;">
        <h3 style="background:var(--tertiaryColor); text-align:left">Post Opening Balances</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm" style="padding:10px 0">
            <div class="inputs" style="gap:.7rem; align-items:flex-start">
                <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                <div class="data" style="width:30%;">
                    <label for="exp_date">Transaction Date</label>
                    <input type="date" name="exp_date" id="exp_date" value="<?php echo date("Y-m-d", strtotime($opening_date))?>">
                </div>
                <div class="data" style="width:30%;">
                    <label for="ledger"> Ledger</label>
                    <input type="text" name="ledger" id="ledger" placeholder="search ledger name" onkeyup="getAccountLedger(this.value)">
                    <div id="search_results" style="width:100%!important;position:relative">

                    </div>
                </div>
                <input type="hidden" name="bal_ledger" id="bal_ledger">
                <div class="data" style="width:30%;">
                    <label for="trans_type" class="ledger">Transaction type</label>
                    <select name="trans_type" id="trans_type">
                        <option value="">Select Transaction type</option>
                        <option value="Debit">DEBIT</option>
                        <option value="Credit">CREDIT</option>
                    </select>
                </div>
                <div class="data" style="width:30%;">
                    <label for="amount" class="ledger">Balance Value (NGN)</label>
                    <input type="text" name="amount" id="amount" required placeholder="5000">
                </div>
                <!-- 
                <div class="data" style="width:40%;">
                    <label for="contra"><span class="ledger">Contra Ledger </span>(Cash/Bank)</label>
                    <select name="contra" id="contra">
                        <option value="" selected>Select Contra ledger</option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                        <?php }?>
                    </select>
                </div> -->
                
                <div class="data" style="width:60%;">
                    <label for="details"> Description</label>
                    <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction" style="color:var(--moreColor);font-weight:bold">Opening Balance as at "01-Jan-2024"</textarea>
                </div>
                <div class="data" style="width:30%; margin:5px 0">
                    <button type="submit" id="post_exp" name="post_exp" onclick="postOpeningBalance()">Post Balance <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
