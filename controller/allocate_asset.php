<?php
    $id = htmlspecialchars(stripslashes($_POST['asset_id']));
    $location = htmlspecialchars(stripslashes($_POST['location']));
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";

    //allocate asset
    $update_expense = new Update_table();
    $update_expense->update('assets', 'location', 'asset_id',$location, $id);
    if($update_expense){
        echo "<div class='success'><p>Asset Allocated successfully!</p></div>";
    }