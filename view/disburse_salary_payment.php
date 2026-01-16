<div id="package">
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
        $user = $_SESSION['user_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['date']) && isset($_GET['amount'])){
        $payroll_month = htmlspecialchars(stripslashes($_GET['date']));
        $store = htmlspecialchars(stripslashes($_SESSION['store_id']));
        $amount = $_GET['amount'];
        //get payroll details
        $get_details = new selects();
    
    
      
    

?>
<div class="info" style="margin:0!important; width:90%!important"></div>
<a style="border-radius:15px; background:brown;color:#fff;padding:10px; box-shadow:1px 1px 1px #222; position:fixed" href="javascript:void(0)" onclick="showPage('disburse_salary.php')"><i class="fas fa-angle-double-left"></i> Return</a>
    <div class="displays allResults" style="width:60%;">
    
        <!-- payment form -->
        <?php
        
        //generate deposit receipt
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 3);
            $ran_num .= $random_num;
        }
        $receipt_id = "SAL".$todays_date.$ran_num.$user.$store;
       
       
?>


    <div class="fund_account" style="width:50%; margin:5px 0;">
        <h3 style="background:var(--labColor); text-align:left">Disburse Salary For <?php echo date("F, Y", strtotime($payroll_month))?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <div class="details_forms">
            <section class="addUserForm" style="width:90%">
                <div class="inputs" style="flex-wrap:wrap">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $receipt_id?>">
                    <input type="hidden" name="posted" id="posted" value="<?php echo $user?>">
                    
                    <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                    <input type="hidden" name="payroll_month" id="payroll_month" value="<?php echo $payroll_month?>">
                    
                    <div class="data" style="width:100%; margin:5px 0">
                        <label for="amount"> Transaction Date</label>
                        <input type="date" name="trans_date" id="trans_date" value="<?php echo date('Y-m-d')?>">
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <label for="amount"> Amount</label>
                        <input type="text"  readonly value="<?php echo "₦" . number_format($amount, 2)?>">
                        <input type="hidden" name="amount" id="amount" value="<?php echo $amount?>">
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
                        <textarea name="details" id="details" cols="30" rows="5" placeholder="Enter a detailed description of the transaction">Salary for <?php echo date("F, Y", strtotime($payroll_month))?></textarea>
                    </div>
                    <div class="data" style="width:50%; margin:5px 0">
                        <button type="submit" id="post_exp" name="post_exp" onclick="disburseSalary()">Post payment <i class="fas fa-cash-register"></i></button>
                    </div>
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