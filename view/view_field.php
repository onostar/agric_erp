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
    <div class="add_user_form priceForm" style="margin:10px 50px;">
        <h3 style="background:var(--tertiaryColor)">UPDATE "<?php echo strtoupper($row->field_name)?>" DETAILS</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:1rem; justify-content:left">
                <!-- <div class="data item_head"> -->
                    <input type="hidden" name="field_id" id="field_id" value="<?php echo $id?>" required>
                <?php if($row->customer != 0){?>
                <div class="data" style="width:31%">
                    <label for="customer">Client</label>
                    <input type="text" name="item" id="item" value="<?php echo $customer?>" readonly>
                </div>
                <?php }?>
                <div class="data" style="width:31%">
                    <label for="field">Field Name</label>
                    <input type="text" name="field" id="field" value="<?php echo $row->field_name?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="sales_price">Field Size (Plot)
                    <input type="text" name="field_size" id="field_size" value="<?php echo $row->field_size?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="soil_type">Soil Type</label>
                    <input type="text" name="soil_type" id="soil_type" value="<?php echo $row->soil_type?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="soil_ph">Soil PH</label>
                    <input type="text" name="soil_ph" id="soil_ph" value="<?php echo $row->soil_ph?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="topography">Topography</label>
                    <input type="text" name="topography" id="topography" value="<?php echo $row->topography?>">
                </div>
                 <div class="data" style="width:31%;">
                   <label for="location"> Location</label>
                   <input type="text" name="location" id="location" value="<?php echo $row->location?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="latitude">Latitude</label>
                    <input type="number" name="latitude" id="latitude" value="<?php echo $row->latitude?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="longitude">Longitude</label>
                    <input type="number" name="longitude" id="longitude" value="<?php echo $row->longitude?>">
                </div>
                
               <!-- <div class="data" style="width:31%">
                    <label for="purchase_cost">Purchase Cost (NGN)</label>
                    <input type="number" name="purchase_cost" id="purchase_cost" value="<?php echo $row->purchase_cost?>">
                </div> -->
                <div class="data" style="width:auto">
                    <button type="button" id="change_price" name="change_price" onclick="updateField()">Update <i class="fas fa-save"></i></button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:15px; border:1px solid #fff;box-shadow:1px 1px 1px #222; color:#fff' onclick="showPage('farm_fields.php')">Return <i class='fas fa-angle-double-left'></i></a>
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