<?php
   
        $item = $_GET['item'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/delete.php";

        //get asset details
        $check = new selects();
        $details = $check->fetch_details_cond('assets', 'asset_id', $item);
        foreach($details as $detail){
            $acn = $detail->ledger;
        }
        //get ledger details
        $ledg = new selects();
        $ledgers = $ledg->fetch_details_cond('ledgers', 'acn', $acn);
        foreach($ledgers as $ledger){
            $ledger_name = $ledger->ledger;
        }
        //delete item
            $delete = new deletes();
            $delete->delete_item('assets', 'asset_id', $item);
            if($delete){
                //check if asset is a field asset
                if($ledger_name == "LAND AND BUILDING"){
                    $delete->delete_item('fields', 'asset_id', $item);
                }
                echo "<div class='success'><p>Asset removed from register! <i class='fas fa-thumbs-up'></i></p></div>";
            }
        // }
?>
