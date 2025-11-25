<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_SESSION['user_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $customer = htmlspecialchars(stripslashes($_POST['customer']));
        $duration = htmlspecialchars(stripslashes($_POST['duration']));
        $rate = htmlspecialchars(stripslashes($_POST['rate']));
        $amount = htmlspecialchars(stripslashes($_POST['amount']));
        $total = htmlspecialchars(stripslashes($_POST['total_naira']));
        $currency = htmlspecialchars(stripslashes($_POST['currency']));
        $start_date = htmlspecialchars(stripslashes($_POST['start_date']));
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";

    }else{
        echo "Your session has expired! Kindly login again to continue";
    }
    