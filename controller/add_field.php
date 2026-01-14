<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $farm = $_SESSION['store_id'];
    $date = date("Y-m-d H:i:s");
    $field = strtoupper(htmlspecialchars(stripslashes($_POST['field'])));
    $size = htmlspecialchars(stripslashes($_POST['field_size']));
    $soil_type = htmlspecialchars(stripslashes($_POST['soil_type']));
    $ph = htmlspecialchars(stripslashes($_POST['soil_ph']));
    // $amount = htmlspecialchars(stripslashes($_POST['purchase_cost']));
    $latitude = htmlspecialchars(stripslashes($_POST['latitude']));
    $longitude = htmlspecialchars(stripslashes($_POST['longitude']));
    $location = htmlspecialchars(stripslashes($_POST['location']));
    $topography = ucwords(htmlspecialchars(stripslashes($_POST['topography'])));
    $data = array(
        'field_name' => $field,
        'farm' => $farm,
        'field_size' => $size,
        'soil_type' => $soil_type,
        'soil_ph' => $ph,
        'latitude' => $latitude,
        'longitude' => $longitude,
        'location' => $location,
        // 'purchase_cost' => $amount,
        'topography' => $topography,
        'created_by' => $user,
        'created_at' => $date
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //check if item already Exist
    $check = new selects();
    $results = $check->fetch_count_2cond('fields', 'farm', $farm, 'field_name', $field);
    if($results > 0){
        echo "<p class='exist'><span>$field</span> already exists</p>";
    }else{
        //create item
        $add_data = new add_data('fields', $data);
        $add_data->create_data();
        if($add_data){
            //get last inserted id
            $ids = $check->fetch_lastInserted('fields', 'field_id');
            $id = $ids->field_id;
            echo "<div class='success'><p><strong>$field</strong> created successfully! Assign to Client</p></div>";

?>
<div id="farm_fields" class="displays">
    <style>
        label{
            font-size:.8rem!important;
        }
    </style>
<?php


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
    <div class="add_btn">
        <button onclick="showPage('add_customer.php')">Add New Customer <i class="fas fa-user-plus"></i></button>
        <div class="clear"></div>
    </div>
    <div class="add_user_form priceForm" style="margin:10px 20px; width:70%;">
        <h3 style="background:var(--tertiaryColor)">Assign "<?php echo strtoupper($row->field_name)?>" to client</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem; justify-content:left">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="field_id" id="field_id" value="<?php echo $id?>" required>
                <div class="data" style="width:61%">
                    <label for="customer">Client</label>
                    <input type="text" name="item" id="item" value="<?php echo $customer?>" oninput="getFieldOwners(this.value)" placeholder="Search client name">
                    <div class="search_results" id="search_results" style="position:relative;">

                    </div>
                    <input type="hidden" id="customer" name="customer" value="<?php echo$row->customer?>">
                </div>
                <div class="data" style="width:35%">
                    <label for="duration">Contact Duration</label>
                    <select name="duration" id="duration">
                        <option value="" selected disabled>Select Contract Duration</option>
                        <option value="3">3 Years</option>
                        <option value="5">5 Years</option>
                        <option value="10">10 Years</option>
                    </select>
                    
                </div>
               
                <div class="data" style="width:31%">
                    <label for="purchase_cost">Purchase Amount (NGN)</label>
                    <input type="number" id="purchase_cost" name="purchase_cost" value="0.00" required oninput="getTotalDue()">
                </div>
                <div class="data" style="width:31%">
                    <label for="discount">Discount (NGN)</label>
                    <input type="number" id="discount" name="discount" value="0.00" required oninput="getTotalDue()">
                </div>
                <div class="data" style="width:31%">
                    <label for="total_due">Total Due (NGN)</label>
                    <input type="text" id="due" name="due" value="0.00" style="background:#fdfdfd;color:#222" readonly>
                    <input type="hidden" id="total_due" name="total_due" value="0.00" readonly>
                </div>
                 <div class="data" style="width:31%">
                    <label for="payment_duration">Purchase Payment Duration</label>
                    <select name="payment_duration" id="payment_duration" onchange="calculateInstallments()">
                        <option value="" selected disabled>Select Payment Duration</option>
                        <option value="3">3 Months</option>
                        <option value="6">6 Months</option>
                        <option value="1">Outright Purchase</option>
                        
                    </select>
                </div>
                    <input type="hidden" id="installment_amount" name="installment_amount">
                
                <!-- <div class="data" style="width:31%">
                    <label for="installments">Installment Amount (NGN)</label>
                    <input type="text" id="install" name="install" style="background:#fff; color:green" value="0.00" readonly>
                    <input type="hidden" id="installment_amount" name="installment_amount">
                </div> -->
                <div class="data" style="width:31%">
                    <label for="rent_percentage">Rent Percentage (%)</label>
                    <select name="rent_percentage" id="rent_percentage" onchange="calculateRent()">
                        <option value="" selected disabled>Select Rent percentage</option>
                        <option value="25">25%</option>
                        <option value="36">36%</option>
                    </select>
                </div>
                <div class="data" style="width:31%">
                    <label for="annual_rent">Annual Rent</label>
                    <input type="text" id="rent" name="rent" style="background:#fff; color:green" value="0.00" readonly>
                    <input type="hidden" id="annual_rent" name="annual_rent">
                </div>
                <div class="data" style="width:31%">
                    <label for="documentation">Documentation Fee (NGN)</label>
                    <input type="number" id="documentation" name="documentation" required>
                </div>
                <div class="data" style="width:31%">
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
<?php
        
    }