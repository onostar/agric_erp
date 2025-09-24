<?php
    $ledger_id = htmlspecialchars(stripslashes($_POST['ledger_id']));
    $ledger = strtoupper(htmlspecialchars(stripslashes($_POST['ledger_name'])));
    $group = htmlspecialchars(stripslashes($_POST['group']));
    $sub_group = ucwords(htmlspecialchars(stripslashes(($_POST['sub_group']))));
    $class = htmlspecialchars(stripslashes(($_POST['account_class'])));

   
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";

       //update customer
       $update_data = new Update_table();
       $update_data->update_quadruple('ledgers', 'ledger', $ledger, 'account_group',$group, 'sub_group', $sub_group, 'class', $class, 'ledger_id', $ledger_id);
       if($update_data){
           echo "<div class='success'><p>$ledger</span> details updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
       }
   