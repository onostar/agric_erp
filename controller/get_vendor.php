<?php
    session_start();
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $toDate = htmlspecialchars(stripslashes($_POST['toDate']));
    $fromDate = htmlspecialchars(stripslashes($_POST['fromDate']));
    
    $get_item = new selects();
    $rows = $get_item->fetch_details_likeCond('vendors', 'vendor', $customer);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="getVendorStatement('<?php echo $row->vendor_id?>', '<?php echo $fromDate?>', '<?php echo $toDate?>')"><?php echo $row->vendor?></a>
    </div>
   
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>