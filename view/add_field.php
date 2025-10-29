<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
?>

<div id="add_room" class="displays">
        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('farm_fields.php')" title="Return fot farm fields">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:35%; margin:20px"></div>
    <div class="add_user_form" style="width:50%; margin:20px">
        <h3 style="background:var(--primaryColor)">Create a Farm Field</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:.5rem;">
                <div class="data" style="width:48%;">
                    <label for="field"> Field Name</label>
                    <input type="text" name="field" id="field" required placeholder="Input item name">
                </div>
                <div class="data" style="width:48%;">
                   <label for="field_size"> Field Size (Hec)</label>
                   <input type="number" name="field_size" id="field_size" required placeholder="Input field size in hectares">
                </div>
                <div class="data" style="width:48%;">
                   <label for="soil_type"> Soil Type</label>
                   <input type="text" name="soil_type" id="soil_type" required>
                </div>
                <div class="data" style="width:48%;">
                   <label for="soil_ph"> Soil PH</label>
                   <input type="number" name="soil_ph" id="soil_ph" required>
                </div>
                <div class="data" style="width:48%;">
                   <label for="location"> Location</label>
                   <input type="text" name="location" id="location" required>
                </div>
                 <div class="data" style="width:48%;">
                   <label for="topography"> Topography</label>
                   <input type="text" name="topography" id="topography">
                </div>
                <div class="data" style="width:48%;">
                   <label for="latitude"> Latitude</label>
                   <input type="number" name="latitude" id="latitude" value=0>
                </div>
                <div class="data" style="width:48%;">
                   <label for="longitude"> Longitude</label>
                   <input type="number" name="longitude" id="longitude" value=0>
                </div>
               
                <div class="data" style="width:48%;">
                   <label for="rent"> Annual Rent Amount (NGN)</label>
                   <input type="number" name="rent" id="rent" value=0>
                </div>
                <div class="data">
                    <button type="button" id="add_item" name="add_item" onclick="addField()">Save record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
