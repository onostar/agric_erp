<?php
    session_start();
    $item = htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";

    $get_item = new selects();
    $rows = $get_item->fetch_details_like('customers', 'customer', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addCorCustomer('<?php echo $row->customer_id?>', '<?php echo $row->customer?>')"><?php echo $row->customer?></a>
    </div>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>