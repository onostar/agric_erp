<?php
    session_start();
    $input= htmlspecialchars(stripslashes($_POST['item']));
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    $get_ledger = new selects();
    $rows = $get_ledger->fetch_details_like2Cond('ledgers', 'ledger', 'acn', $input);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        
    ?>
    <div class="results">
        <a href="javascript:void(0)" onclick="showPage('edit_ledger.php?ledger=<?php echo $row->ledger_id?>')"><?php echo $row->ledger?></a>
    </div>
    <!--  -->
    
<?php
    endforeach;
     }else{
        echo "No resullt found";
     }
?>