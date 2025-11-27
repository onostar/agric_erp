<div id="concentrates" class="displays">
    <style>
        label{
            font-size:.8rem!important;
        }
        @media screen and (max-width: 800px){
             .add_user_form{
                margin:0!important;
             }
            .add_user_form .inputs{
                flex-wrap: wrap!important;
                gap:.3rem;
            }
            .add_user_form .inputs .data{
                width:100%!important;
            }
        }
        
    </style>
<?php
    session_start();
    if(isset($_SESSION['user_id'])){
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    //get rate
    $get_details = new selects();
    $rts = $get_details->fetch_details('exchange_rates');
    if(is_array($rts)){
        foreach($rts as $rt){
            $rate = $rt->rate;
        }
    }else{
        $rate = 0;
    }
            
    ?>
    <div class="add_btn">
        <button onclick="showPage('add_customer.php')">Add New Client <i class="fas fa-user-plus"></i></button>
        <div class="clear"></div>
    </div>
    <div class="add_user_form priceForm" style="margin:10px 20px; width:50%;">
        <h3 style="background:var(--tertiaryColor)">Concentrate Investment</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem; justify-content:left">
                <div class="data" style="width:100%">
                    <label for="customer">Client</label>
                    <input type="text" name="item" id="item" oninput="getFieldOwners(this.value)" placeholder="Search client name">
                    <div class="search_results" id="search_results" style="position:relative;">

                    </div>
                    <input type="hidden" id="customer" name="customer">
                </div>
                <div class="data" style="width:48%">
                    <label for="duration">Contact Duration</label>
                    <select name="duration" id="duration">
                        <option value="3" selected>3 Years</option>
                    </select>
                    
                </div>
                <div class="data" style="width:48%">
                    <label for="payment_duration">Currency</label>
                    <select name="currency" id="currency" onchange="showInvestment()">
                        <option value="" selected disabled>Select Currency</option>
                        <option value="Dollar">Dollar ($)</option>
                        <option value="Naira">Naira (₦)</option>
                        
                    </select>
                </div>
            </div>
            
            <div class="inputs" id="complete_invest">
                <div class="data" style="width:48%">
                    <input type="hidden" name="rate" id="rate" value="<?php echo $rate?>">
                    <label for="">Exchange Rate</label>
                    <input type="text" id="exchange_rate" name="exchange_rate" readonly style="background:#fdfdfd; color:#222">
                </div>
                <div class="data" style="width:48%">
                    <label for="amount" id="amount_currency">Amount</label>
                    <input type="number" id="amount" name="amount" value="0.00" required oninput="getTotalRate()">
                </div>
                <div class="data" style="width:48%; margin:5px 0">
                    <label for="total_in_naira">Total Amount in Naira (₦)</label>
                    <input type="text" id="total_in_naira" name="total_in_naira" style="background:#fff; color:green" value="0.00" readonly>
                    <input type="hidden" id="total_naira" name="total_naira">
                </div>
                <div class="data" style="width:auto; margin:5px 0">
                    <button type="button" style="background:var(--tertiaryColor)" id="change_price" name="change_price" onclick="invest()">Save Investment <i class="fas fa-save"></i></button>
                    
                </div>
                
            </div>
        </form>   
    </div>
    
<?php

    }else{
        echo "Your session has expired! Kindly login again to continue";
    }  
?>
</div>