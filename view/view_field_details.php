<div id="farm_fields" class="displays">
    <style>
        label, input{
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
    <a href="javascript:void(0)" title="close form" style='background:brown; padding:8px; border-radius:15px; border:1px solid #fff;box-shadow:1px 1px 1px #222; color:#fff' onclick="showPage('farm_fields.php')">Return <i class='fas fa-angle-double-left'></i></a>

    <div class="add_user_form priceForm" style="margin:10px; width:100%">
        <h3 style="background:var(--tertiaryColor)"><?php echo strtoupper($row->field_name)?>" DETAILS</h3>
        <form style="text-align:left;">
            <div class="inputs" style="flex-wrap:wrap; gap:.6rem; justify-content:left">
                <?php if($customer != ""){?>
                <div class="data" style="width:24%">
                    <label for="customer">Client Assignd To</label>
                    <input type="text" name="item" id="item" value="<?php echo $customer?>" readonly>
                </div>
                <?php }?>
                <div class="data" style="width:24%">
                    <label for="field">Field Name</label>
                    <input type="text" name="field" id="field" value="<?php echo $row->field_name?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="sales_price">Field Size (Hec)
                    <input type="text" name="field_size" id="field_size" value="<?php echo $row->field_size?> Hectares">
                </div>
                <div class="data" style="width:24%">
                    <label for="soil_type">Soil Type</label>
                    <input type="text" name="soil_type" id="soil_type" value="<?php echo $row->soil_type?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="soil_ph">Soil PH</label>
                    <input type="text" name="soil_ph" id="soil_ph" value="<?php echo $row->soil_ph?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="topography">Topography</label>
                    <input type="text" name="topography" id="topography" value="<?php echo $row->topography?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="latitude">Latitude</label>
                    <input type="number" name="latitude" id="latitude" value="<?php echo $row->latitude?>">
                </div>
                <div class="data" style="width:24%">
                    <label for="longitude">Longitude</label>
                    <input type="number" name="longitude" id="longitude" value="<?php echo $row->longitude?>">
                </div>
                
               <div class="data" style="width:24%">
                    <label for="rent">Rent (NGN)</label>
                    <input type="text" name="rent" id="rent" value="<?php echo "₦".number_format($row->rent, 2)?>">
                </div>
            </div>
            <hr>
            <h4 style="margin:5px 0 0 0; font-size:.9rem;">Geolocation View</h4>
            
            
        </form>   
    </div>
<div id="map" style="height: 300px; width: 100%; border-radius: 10px;"></div>

<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    var lat = <?php echo $row->latitude ?: 9.0820 ?>;
    var lng = <?php echo $row->longitude ?: 8.6753 ?>;

    var map = L.map('map').setView([lat, lng], 14);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
    }).addTo(map);

    let marker = L.circleMarker([lat, lng], {
        color: 'green',
        radius: 8,
        fillOpacity: 0.8
    }).addTo(map);

    marker.bindPopup(
        "<b><?php echo strtoupper($row->field_name) ?></b><br>" +
        "Client: <?php echo $customer ?><br>" +
        "Size: <?php echo $row->field_size ?> Hectares<br>" +
        "Rent: ₦<?php echo number_format($row->rent, 2) ?>"
    );

    // Important if map is loaded inside a hidden section
    setTimeout(() => map.invalidateSize(), 500);
</script>


<?php

    endforeach;
     }
    }    
?>
</div>

