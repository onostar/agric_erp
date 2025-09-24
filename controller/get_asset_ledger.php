<?php

    $asset = htmlspecialchars(stripslashes($_POST['asset']));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_cat = new selects();
    $rows = $get_cat->fetch_details_cond('assets', 'asset_id', $asset);
?>
<!-- <option value="">Select Sub-group</option> -->
<?php
    if(gettype($rows) == 'array'){
        foreach ($rows as $row) {
        $ledger = $row->ledger;
    }
    //get ledger details
    $get_ledger = new selects();
    $heads = $get_ledger->fetch_details_cond('ledgers', 'acn', $ledger);
    foreach($heads as $head){
?>
    <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
<?php
        }   
    }else{
        echo "<option value=''selected>No ledger available</option>";
    }
?>