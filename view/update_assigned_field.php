<div id="farm_fields" class="displays">
    <style>
        label{
            font-size:.8rem!important;
        }
    </style>
<?php

    if (isset($_GET['assigned'])){
        $id = $_GET['assigned'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('assigned_fields', 'assigned_id', $id);
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
            //get field name
            $fname = $get_item->fetch_details_group('fields', 'field_name', 'field_id', $row->field);
            $field_name = $fname->field_name;
            
    ?>
    <!-- <div class="add_btn">
        <button onclick="showPage('add_customer.php')">Add New Customer <i class="fas fa-user-plus"></i></button>
        <div class="clear"></div>
    </div> -->
    <div class="add_user_form priceForm" style="margin:10px 20px; width:70%;">
        <h3 style="background:var(--tertiaryColor)">Update Field details assigned to "<?php echo strtoupper($customer)?>"</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem; justify-content:left">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" id="assigned_id" name="assigned_id" value="<?php echo $id?>">
                <div class="data" style="width:48%">
                    <label for="Field">Field</label>
                    <select name="field_id" id="field_id">
                        <option value="<?php echo $row->field?>"><?php echo $field_name?></option>
                        <?php
                            //get all fields
                            $fds = $get_item->fetch_details_order('fields','field_name');
                            if(is_array($fds)){
                                foreach($fds as $fd){
                            
                        ?>
                        <option value="<?php echo $fd->field_id?>"><?php echo $fd->field_name?></option>
                        <?php }}?>
                    </select>
                </div>
                <div class="data" style="width:48%">
                    <label for="customer">Client</label>
                    <input type="text" name="item" id="item" value="<?php echo $customer?>" oninput="getFieldOwners(this.value)" placeholder="Search client name">
                    <div class="search_results" id="search_results" style="position:relative;">

                    </div>
                    <input type="hidden" id="customer" name="customer" value="<?php echo $row->customer?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="duration">Contract Duration</label>
                    <select name="duration" id="duration">
                        <option value="<?php echo $row->contract_duration?>" selected><?php echo $row->contract_duration?> Years</option>
                        <option value="3">3 Years</option>
                        <option value="5">5 Years</option>
                        <option value="10">10 Years</option>
                    </select>
                    
                </div>
                <div class="data" style="width:31%">
                    <label for="field_size">Size (Plot)</label>
                    <input type="number" id="field_size" name="field_size" value="<?php echo $row->field_size?>" required>
                </div>
                <div class="data" style="width:31%">
                    <label for="purchase_cost">Purchase Amount (NGN)</label>
                    <input type="number" id="purchase_cost" name="purchase_cost" value="<?php echo $row->purchase_cost?>" required oninput="getTotalDue()">
                </div>
                <div class="data" style="width:31%">
                    <label for="discount">Discount (NGN)</label>
                    <input type="number" id="discount" name="discount" value="<?php echo $row->discount?>" required oninput="getTotalDue()">
                </div>
                <div class="data" style="width:31%">
                    <label for="total_due">Total Due (NGN)</label>
                    <input type="text" id="due" name="due" value="<?php echo number_format($row->total_due, 2)?>" style="background:#fdfdfd;color:#222" readonly>
                    <input type="hidden" id="total_due" name="total_due" value="<?php echo $row->total_due?>" readonly>
                </div>
                 <div class="data" style="width:31%">
                    <label for="payment_duration">Purchase Payment Duration</label>
                    <select name="payment_duration" id="payment_duration" onchange="calculateInstallments()">
                        <?php
                            $pay_text;
                            if($row->payment_duration == "1"){
                                $pay_text = "Outright Purchase";
                            }else{
                                $pay_text = "$row->payment_duration Months";
                            }
                        ?>
                        <option value="<?php echo $row->payment_duration?>" selected><?php echo $pay_text?></option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="1">Outright Purchase</option>
                        
                    </select>
                </div>
                <input type="hidden" id="installment_amount" name="installment_amount" value="<?php echo $row->installment?>">
                
               <!--  <div class="data" style="width:31%">
                    <label for="installments">Installment Amount (NGN)</label>
                    <input type="text" id="install" name="install" style="background:#fff; color:green" value="0.00" readonly>
                    <input type="hidden" id="installment_amount" name="installment_amount">
                </div> -->
                <div class="data" style="width:31%">
                    <label for="rent_percentage">Rent Percentage (%)</label>
                    <select name="rent_percentage" id="rent_percentage" onchange="calculateRent()">
                        <option value="<?php echo $row->rent_percentage?>" selected><?php echo $row->rent_percentage?>%</option>
                        <option value="25">25%</option>
                        <option value="36">36%</option>
                    </select>
                </div>
                <div class="data" style="width:31%">
                    <label for="annual_rent">Annual Rent</label>
                    <input type="text" id="rent" name="rent" style="background:#fff; color:green" value="<?php echo number_format($row->annual_rent, 2)?>" readonly>
                    <input type="hidden" id="annual_rent" name="annual_rent" value="<?php echo $row->annual_rent?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="documentation">Documentation Fee (NGN)</label>
                    <input type="number" id="documentation" name="documentation" value="<?php echo $row->documentation?>"required>
                </div>
                <div class="data" style="width:31%">
                    <label for="start_date">Start Date</label>
                    <input type="date" id="start_date" name="start_date" value="<?php echo date("Y-m-d", strtotime($row->start_date))?>">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" id="change_price" name="change_price" onclick="updateAssignedField()">Update <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:15px; border:1px solid #fff;box-shadow:1px 1px 1px #222; color:#fff' onclick="showPage('pending_fields.php')">Return <i class='fas fa-angle-double-left'></i></a>
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