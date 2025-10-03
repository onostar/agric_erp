<?php
    session_start();
    $input= htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
   
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_likeCond('customers', 'customer', $input);
    if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addFieldOwner('<?php echo $row->customer_id?>', '<?php echo $row->customer?>')"><?php echo $row->customer?></a>
    </div>
       
        <?php
        endforeach;

        }else{
            echo "No resullt found";
        }
    
?>