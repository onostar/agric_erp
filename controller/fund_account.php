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
    
    if(isset($_GET['customer'])){
        $customer_id = $_GET['customer'];
        //get customer details;
        $get_details = new selects();
        $rows = $get_details->fetch_details_cond('customers', 'customer_id', $customer_id);
        foreach($rows as $row){
            $customer = $row->customer;
            $balance = $row->wallet_balance;
            $debt = $row->amount_due;
            $acn = $row->acn;
            // $ledger = $row->ledger_id;
        }
        //generate deposit receipt
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 5; $i++){
            $random_num = random_int(0, 3);
            $ran_num .= $random_num;
        }
        $receipt_id = "DEP".$todays_date.$store.$ran_num.$user_id;
        //get balance from transactions
        $get_balance = new selects();
        $bals = $get_balance->fetch_account_balance($acn);
        if(gettype($bals) == 'array'){
            foreach($bals as $bal){
                $balance = $bal->balance;
            }
        }
?>
<div class="back_invoice">
    <button class="page_navs" id="back" onclick="showPage('../controller/customer_statement.php?customer=<?php echo $customer_id?>')"><i class="fas fa-angle-double-left"></i> Back</button>

    <a href="javascript:void(0)" onclick="showPage('debt_payment.php?customer=<?php echo $customer_id?>') "title="view customer invoices" style="background:green; color:#fff; padding:10px; border-radius:10px; box-shadow:1px 1px 1px #222">View Invoices <i class="fas fa-receipt"></i></a>
</div>
<div id="deposit" class="displays">
    <div class="info" style="width:50%; margin:5px 0;"></div>
    <div class="fund_account" style="width:80%; margin:5px 0;">
        <h3 style="background:var(--otherColor); text-align:left">Post customer payments</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <div class="details_forms">
            <section class="addUserForm">
                <div class="inputs" style="flex-wrap:wrap">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $receipt_id?>">
                    <input type="hidden" name="posted" id="posted" value="<?php echo $user_id?>">
                    <input type="hidden" name="customer" id="customer" value="<?php echo $customer_id?>">
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                    
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="amount"> Transaction Date</label>
                        <input type="date" name="trans_date" id="trans_date" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <label for="amount"> Amount paid</label>
                        <input type="text" name="amount" id="amount" required placeholder="0.00">
                    </div>
                    <div class="data" style="width:45%">
                        <label for="Payment_mode"><span class="ledger">Dr. Ledger</span> (Cash/Bank)</label>
                        <select name="payment_mode" id="payment_mode" onchange="checkMode(this.value)">
                            <option value=""selected>Select payment option</option>
                            <option value="Cash">Cash</option>
                            <option value="POS">POS</option>
                            <option value="Transfer">Transfer</option>
                        </select>
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
                        <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction"></textarea>
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <button type="submit" id="post_exp" name="post_exp" onclick="customerDeposit()">Post payment <i class="fas fa-cash-register"></i></button>
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
                        <input type="text" value="<?php echo "-₦".number_format($balance, 2)?>" style="color:red;">
                    </div>
                    <?php }else{?>
                    <div class="data">
                        <label for="balance">Account balance:</label>
                        <input type="text" value="<?php echo "₦".number_format($balance, 2)?>" style="color:green;">
                    </div>
                    <?php }?>
                    
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