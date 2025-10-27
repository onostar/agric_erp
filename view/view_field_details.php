<?php
session_start();
if(isset($_SESSION['user'])){
    include "../classes/dbh.php";
    include "../classes/select.php";
    $username = $_SESSION['user'];

    // Fetch user
    $fetch_user = new selects();
    $users = $fetch_user->fetch_details_cond('users', 'username', $username);
    foreach($users as $user){
        $fullname = $user->full_name;
        $role = $user->user_role;
        $user_id = $user->user_id;
        $store_id = $user->store;
    }

    $_SESSION['user_id'] = $user_id;
    $_SESSION['role'] = $role;

    // Company
    $fetch_comp = new selects();
    $comps = $fetch_comp->fetch_details('companies');
    foreach($comps as $com){
        $company = $com->company;
        $comp_id = $com->company_id;
        $logo = $com->logo;
    }

    $_SESSION['company_id'] = $comp_id;
    $_SESSION['company'] = $company;
    $_SESSION['company_logo'] = $logo;

    // Store
    $get_store = new selects();
    $strs = $get_store->fetch_details_cond('stores', 'store_id', $store_id);
    foreach($strs as $str){
        $store = $str->store;
        $store_address = $str->store_address;
        $phone = $str->phone_number;
    }

    $_SESSION['store_id'] = $store_id;
    $_SESSION['store'] = $store;
    $_SESSION['address'] = $store_address;
    $_SESSION['phone'] = $phone;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Field Geolocation | <?php echo $company ?></title>

<!-- Optimized meta for ERP and farm management -->
<meta name="description" content="View and manage clients’ farmland locations using real satellite and map views. <?php echo $company ?> ERP helps you track rented fields, monitor assets, and visualize customer locations with precision.">
<meta name="keywords" content="ERP software, agriculture ERP, field management, farm ERP, satellite map, land tracking, client management, DorthPro ERP, Onostar Media, Nigeria ERP, inventory and farm system">

<link rel="icon" type="image/png" href="../images/icon.png">
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

<style>
    body {
        margin: 0;
        padding: 0;
        overflow: hidden;
        font-family: "Segoe UI", Arial, sans-serif;
    }

    #map {
        height: 100vh;
        width: 100%;
    }

    .close-btn {
        position: fixed;
        bottom: 10vh;
        left: 20px;
        background: brown;
        padding: 10px 18px;
        color: white;
        font-weight: bold;
        border-radius: 10px;
        border: 1px solid #fff;
        box-shadow: 2px 2px 5px #000;
        cursor: pointer;
        z-index: 9999;
        transition: background 0.3s;
    }

    .close-btn:hover {
        background: darkred;
    }

    .leaflet-control-layers {
        font-size: 14px;
        background: rgba(255, 255, 255, 0.9);
        border-radius: 8px;
        box-shadow: 1px 1px 3px rgba(0,0,0,0.4);
        padding: 4px;
    }

    .leaflet-popup-content-wrapper {
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.3);
    }
</style>
</head>
<body>

<?php
if (isset($_GET['field'])){
    $id = $_GET['field'];
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('fields', 'field_id', $id);
    if(gettype($rows) == 'array'){
        foreach($rows as $row){
            $latitude = $row->latitude;
            $longitude = $row->longitude;
            $field_name = $row->field_name;
            $customer = $row->customer;
            $size = $row->field_size;
        }

        // Get customer
        $cus = $get_item->fetch_details_cond('customers', 'customer_id', $customer);
        $owner = (is_array($cus) && count($cus) > 0) ? $cus[0]->customer : "N/A";
    }
?>
<button class="close-btn" onclick="window.close()">Close ✖</button>
<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
  const lat = <?php echo floatval($latitude ?: 9.0820); ?>;
  const lng = <?php echo floatval($longitude ?: 8.6753); ?>;

  // Base layers
  const satellite = L.tileLayer('https://{s}.google.com/vt/lyrs=s,h&x={x}&y={y}&z={z}', {
    maxZoom: 20,
    subdomains: ['mt0','mt1','mt2','mt3'],
    attribution: '&copy; Google Maps'
  });

  const street = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
    attribution: '&copy; OpenStreetMap'
  });

  // Initialize map
  const map = L.map('map', {
    center: [lat, lng],
    zoom: 16,
    layers: [satellite]
  });

  // Layer control (toggle)
  const baseMaps = {
    "Satellite View": satellite,
    "Street View": street
  };
  L.control.layers(baseMaps).addTo(map);

  // Marker
  const marker = L.circleMarker([lat, lng], {
    color: 'green',
    radius: 8,
    fillOpacity: 0.9
  }).addTo(map);

  marker.bindPopup(`
    <b><?php echo strtoupper($field_name); ?></b><br>
    Owner: <?php echo $owner; ?><br>
    Size: <?php echo $size; ?> Hectares<br>
    Latitude: ${lat}<br>
    Longitude: ${lng}
  `).openPopup();
});
</script>

<?php } ?>
</body>
</html>
<?php
}else{
    echo "Your session has expired! Please login again to continue.";
}
?>
