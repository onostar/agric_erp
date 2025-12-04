<div id="fund_account">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        $store = $_SESSION['store_id'];
        // echo $user_id;
    
    if(isset($_GET['customer']) && isset($_GET['investment'])){
        $customer_id = $_GET['customer'];
        $investment = $_GET['investment'];
        //get customer details;
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('customers', 'customer_id', $customer_id);
        foreach($rows as $row){
            $customer = $row->customer;
            $acn = $row->acn;
        }
        //generate deposit receipt
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 5; $i++){
            $random_num = random_int(0, 3);
            $ran_num .= $random_num;
        }
        $receipt_id = "LP".$todays_date.$ran_num.$user_id.$investment;
        //get balance from transactions
        $bals = $get_details->fetch_account_balance($acn);
        if(gettype($bals) == 'array'){
            foreach($bals as $bal){
                $balance = $bal->balance;
            }
        }
        //get investmentdetails
        $lns = $get_details->fetch_details_cond('investments', 'investment_id', $investment);
        foreach($lns as $lns){
            $amount_invested = $lns->amount;
            $currency = $lns->currency;
            $start_date = $lns->start_date;
            $total_in_naira = $lns->total_in_naira;
        }
        if($currency == "Dollar"){
            $icon = "$";
        }else{
            $icon = "₦";
        }
        $debt = $amount_invested;

?>
<div class="back_invoice">
    <button class="page_navs" id="back" onclick="showPage('return_principal.php')"><i class="fas fa-angle-double-left"></i> Back</button>

</div>
<div id="deposit" class="displays">
    <div class="info" style="width:70%; margin:5px 0;"></div>
    <div class="fund_account" style="width:80%; margin:5px 0;">
        <h3 style="background:var(--labColor); text-align:left">Return client investment principal</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <div class="details_forms">
            <section class="addUserForm">
                <div class="inputs" style="flex-wrap:wrap">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $receipt_id?>">
                    <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                    <input type="hidden" name="customer" id="customer" value="<?php echo $customer_id?>">
                    <input type="hidden" name="balance" id="balance" value="<?php echo $debt?>">
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                    <input type="hidden" name="investment" id="investment" value="<?php echo $investment?>">
                    
                    <div class="data" style="width:48%; margin:5px 0">
                        <label for="amount"> Transaction Date</label>
                        <input type="date" name="trans_date" id="trans_date" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="data" style="width:48%">
                        <label for="Payment_mode"><span class="ledger">Dr. Ledger</span> (Cash/Bank)</label>
                        <select name="payment_mode" id="payment_mode" onchange="checkMode(this.value)">
                            <option value=""selected>Select payment option</option>
                            <option value="Cash">Cash</option>
                            <option value="POS">POS</option>
                            <option value="Transfer">Transfer</option>
                        </select>
                    </div>
                    <div class="data" style="width:48%; margin:5px 0">
                        <label for="amount"> Amount</label>
                        <input type="text" readonly value="<?php echo $icon.number_format($amount_invested, 2)?>">
                        <input type="hidden" id="amount" name="amount" value="<?php echo $amount_invested?>">
                    </div>
                    <div class="data" style="width:48%; margin:5px 0">
                        <label for="amount"> Value in Naira</label>
                        <input type="text" readonly value="<?php echo "₦".number_format($total_in_naira, 2)?>">
                        <input type="hidden" name="amount_in_naira" id="amount_in_naira" value="<?php echo $total_in_naira?>">
                    </div>
                    
                    <div class="data" id="selectBank"  style="width:100%!important">
                        <select name="bank" id="bank">
                            <option value=""selected>Select Bank</option>
                            <?php
                                $get_bank = new selects();
                                $rows = $get_bank->fetch_details('banks', 10, 10);
                                foreach($rows as $row):
                            ?>
                            <option value="<?php echo $row->bank_id?>"><?php echo $row->bank?></option>
                            <?php endforeach?>
                        </select>
                    </div>
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="details"> Description</label>
                        <textarea name="details" id="details" cols="30" rows="5">Investment Principal Returns</textarea>
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <button type="submit" id="post_exp" name="post_exp" onclick="returnPrincipal()">Post payment <i class="fas fa-cash-register"></i></button>
                    </div>
                </div>
            </section>
            <section class="customer_details" style="height:100%;">
                <div class="inputs">
                    <div class="data">
                        <label for="customer_id">Customer ID:</label>
                        <input type="text" value="<?php echo $acn?>">
                    </div>
                    <div class="data">
                        <label for="customer_name"><span class="ledger" style="color:#fff">Cr. Ledger</span> (Client):</label>
                        <input type="text" value="<?php echo $customer?>">
                    </div>
                    <?php if($balance >= 0){?>
                    <div class="data">
                        <label for="balance">Account balance:</label>
                        <input type="text" value="<?php echo "₦".number_format($balance, 2)?>" style="color:red;">
                    </div>
                    <?php }else{?>
                    <div class="data">
                        <label for="balance">Account balance:</label>
                        <input type="text" value="<?php echo "₦".number_format(0, 2)?>" style="color:green;">
                    </div>
                    <?php }?>
                    <!-- <div class="data">
                        <label for="balance">Payment Due:</label>
                        <input type="text" value="<?php echo "₦".number_format($debt, 2)?>" style="color:red;">
                    </div> -->
                </div>
            </section> 
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