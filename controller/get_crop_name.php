<?php
    session_start();
    $input= htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
   
    $get_crop = new selects();
    $rows = $get_crop->fetch_details_like1Cond('items', 'item_name', $input, 'item_type', 'Crop');
    if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="addCrop('<?php echo $row->item_id?>', '<?php echo $row->item_name?>')"><?php echo $row->item_name?></a>
    </div>
       
        <?php
        endforeach;

        }else{
            echo "No resullt found";
        }
    
?>