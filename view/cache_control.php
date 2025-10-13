<?php
// cache_control.php

header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");

// Set this to true while developing
$isDevelopment = true;

if ($isDevelopment) {
    define("APP_VERSION", time());
} else {
    define("APP_VERSION", "2025.10.11"); // update when new build is deployed
}
