<div id="farm_fields" class="displays">
    <style>
        label{
            font-size:.8rem!important;
        }
    </style>
<?php

    if (isset($_GET['field'])){
        $id = $_GET['field'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('fields', 'field_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
            //GET CUSTOMER
            $cuss = $get_item->fetch_details_cond('customers', 'customer_id', $row->customer);
            if(is_array($cuss)){
                foreach($cuss as $cus){
                    $customer = $cus->customer;
                }
            }else{
                $customer = "";
            }
            
    ?>
    <div class="add_user_form priceForm" style="margin:10px 20px; width:50%;">
        <h3 style="background:var(--tertiaryColor)">Assign "<?php echo strtoupper($row->field_name)?>" to client</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem; justify-content:left">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="field_id" id="field_id" value="<?php echo $id?>" required>
                <div class="data" style="width:100%">
                    <label for="customer">Client</label>
                    <input type="text" name="item" id="item" value="<?php echo $customer?>" oninput="getFieldOwners(this.value)" placeholder="Search client name">
                    <div class="search_results" id="search_results" style="position:relative;">

                    </div>
                    <input type="hidden" id="customer" name="customer" value="<?php echo$row->customer?>">
                </div>
                <div class="data">
                    <label for="duration">Duration</label>
                    <select name="duration" id="duration" onchange="calculateRepayment()">
                        <option value="" selected disabled>Select Contract Duration</option>
                        <option value="1">1 Year</option>
                        <option value="2">2 Years</option>
                        <option value="3">3 Years</option>
                    </select>
                    
                </div>
                <div class="data">
                    <label for="frequency">Repayment Frequency</label>
                    <select name="frequency" id="frequency" onchange="calculateInstallments()">
                        <option value="" selected disabled>Select Repayment Frequency</option>
                        <option value="Weekly">Weekly</option>
                        <option value="Monthly">Monthly</option>
                        <option value="Yearly">Yearly</option>
                    </select>
                </div>
                <div class="data">
                    <label for="rent">Annual Rate (NGN)</label>
                    <input type="text" value="<?php echo number_format($row->rent,2)?>" readonly>
                    <input type="hidden" id="rent" name="rent" value="<?php echo $row->rent?>">
                </div>
                <div class="data">
                    <label for="repayment">Total Repayment (NGN)</label>
                    <input type="text" id="repay" name="repay" style="background:#cdcdcd" value="0.00" readonly>
                    <input type="hidden" id="repayment" name="repayment">
                </div>
                <div class="data">
                    <label for="installments">Installment Amount (NGN)</label>
                    <input type="text" id="install" name="install" style="background:#fff; color:green" value="0.00" readonly>
                    <input type="hidden" id="installment_amount" name="installment_amount">
                </div>
                <div class="data">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" id="change_price" name="change_price" onclick="assignField()">Assign <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:15px; border:1px solid #fff;box-shadow:1px 1px 1px #222; color:#fff' onclick="showPage('assign_field.php')">Return <i class='fas fa-angle-double-left'></i></a>
                </div>
                
            </div>
        </form>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>
</div>