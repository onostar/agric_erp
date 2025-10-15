<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();    
        $user = $_SESSION['user_id'];
    // if(isset($_POST['change_prize'])){
        $supplier = htmlspecialchars(stripslashes($_POST['suppliers']));
        $invoice = htmlspecialchars(stripslashes($_POST['sales_invoice']));
        $date = date("Y-m-d H:i:s");

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        include "../classes/update.php";
        
        //update invoice
        $update_po = new Update_table();
        $update_po->update2cond('purchase_order', 'order_status', 'vendor', 'invoice', 1, $supplier, $invoice);
        
        if($update_po){
            echo "<div class='notify'><p><i class='fas fa-thumbs-up'></i> Purchase Order posted successfully</p></div>";

?>
    <button type="button" onclick="printPO('<?php echo $invoice?>')"style="background:green; color:#fff; padding:5px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222; margin:5px 50px; font-size:.8rem;">Print PO <i class="fas fa-print"></i></button>
<?php
        }