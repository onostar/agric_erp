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
    <option onclick="addCorCustomer('<?php echo $row->customer_id?>', '<?php echo $row->customer?>')">
        <?php echo $row->customer?>
    </option>
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>