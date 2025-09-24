<?php
        session_start();
        $store = $_SESSION['store_id'];
        $id = htmlspecialchars(stripslashes($_POST['invoice_id']));
        $amount = htmlspecialchars(stripslashes($_POST['amount_update']));
        $details = htmlspecialchars(stripslashes($_POST['details_update']));
        $invoice = htmlspecialchars(stripslashes($_POST['invoice']));

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
       
        //update prescription
        $update = new Update_table();
        $update->update_double('invoices', 'amount', $amount, 'details', $details, 'invoice_id', $id);
        // if($update){
?>
<!-- display items with same invoice number -->

<?php
    include "invoice_details.php";
            // }            
        // }
    // }
?>