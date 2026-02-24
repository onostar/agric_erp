<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $beneficiary = htmlspecialchars(stripslashes($_POST['ben_id']));
   
    // instantiate classes
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    include "../classes/delete.php";
    //check if beneficiary already exists
    $check = new selects();
    $results = $check->fetch_details_cond('beneficiaries', 'beneficiary_id', $beneficiary);
    foreach($results as $result){
        $customer = $result->staff;
    }
    $delete = new deletes();
    $delete->delete_item('beneficiaries', 'beneficiary_id', $beneficiary);
        
        if($delete){
            echo "<p class='notify' style='color:#fff;'>Beneficiary removed successfully</p>";
            include "../controller/beneficiaries.php";

        ?>
        
<?php
        
        
    }