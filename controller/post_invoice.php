<?php
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/update.php";
    session_start();
    if(isset($_SESSION['user_id'])){
        $invoice = $_GET['invoice'];
        

    //update all items with this invoice

    $update_invoice = new Update_table();
    $update_invoice->update('invoices', 'invoice_status', 'invoice', 1, $invoice);
    
                
?>
<div id="printBtn">
    <button onclick="printInvoice('<?php echo $invoice?>')">Print Invoice<i class="fas fa-print"></i></button>
</div>
<!--  -->
   
<?php
    // echo "<script>window.print();</script>";
                    // }
                
    }else{
        header("Location: ../index.php");
    } 
?>