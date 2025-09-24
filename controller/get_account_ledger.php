<?php
    session_start();
    $input= htmlspecialchars(stripslashes($_POST['ledger']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    
        $get_customer = new selects();
        $rows = $get_customer->fetch_details_likeCond('ledgers', 'ledger', $input);
        if(gettype($rows) == 'array'){
            foreach($rows as $row):
            
        ?>
        <div class="results">
            <a href="javascript:void(0)" onclick="addAccount('<?php echo $row->ledger_id?>', '<?php echo $row->ledger?>')"><?php echo $row->ledger?></a>
        </div>
       
        <?php
        endforeach;

        }else{
            echo "No resullt found";
        }
    
?>