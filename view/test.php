<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Geolocation</title>

  <!-- Load Leaflet CSS -->
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map {
      height: 400px;
      width: 100%;
      border-radius: 10px;
    }
  </style>
</head>
<body>
    <?php
        if (isset($_GET['field'])){
        $id = $_GET['field'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('fields', 'field_id', $id);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            //GET CUSTOMER
            $latitude = $row->latitude;
            $longitude = $row->longitude;
        }
    }
    ?>
  <h3>Farm Field Geolocation</h3>
  <div id="map"></div>

  <!-- Load Leaflet JS -->
  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      // Example coordinates (Warri, Nigeria area)
      var lat = <?php echo $latitude?>;
      var lng = <?php echo $longitude?>;

      var map = L.map('map').setView([lat, lng], 14);

      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 18,
        attribution: '&copy; OpenStreetMap contributors'
      }).addTo(map);

      let marker = L.circleMarker([lat, lng], {
        color: 'green',
        radius: 8,
        fillOpacity: 0.8
      }).addTo(map);

      marker.bindPopup("<b>Farm Field</b><br>Latitude: " + lat + "<br>Longitude: " + lng);
    });
  </script>
<?php }?>
</body>
</html>
