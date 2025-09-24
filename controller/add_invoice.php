<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $store = $_SESSION['store_id'];
    $customer = htmlspecialchars(stripslashes($_POST['customer']));
    $posted_by = htmlspecialchars(stripslashes($_POST['posted_by']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
    $amount = htmlspecialchars(stripslashes($_POST['amount']));
    $details = ucwords(htmlspecialchars(stripslashes($_POST['details'])));
    $date = date("Y-m-d H:i:s");
    $data = array(
        'customer' => $customer,
        'posted_by' => $posted_by,
        'invoice' => $invoice,
        'details' => $details,
        'amount' => $amount,
        'post_date' => $date,
        'store' => $store
    );
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";

    //get customer details
    $get_customer = new selects();
    $rowss = $get_customer->fetch_details_cond('customers', 'customer_id', $customer);
    foreach($rowss as $rows){
        $customer_name = $rows->customer;
    }
   //check if details exists
   $check = new selects();
   $results = $check->fetch_count_2cond('invoices', 'details', $details, 'invoice', $invoice);
   if($results > 0){
       echo "<p class='exist'>This item already exists for this invoice!</p>";
    include "invoice_details.php";
   }else{
       //create customer
       $add_data = new add_data('invoices', $data);
       $add_data->create_data();
       if($add_data){
?>
<div class="notify"><p>Item added to Invoice order</p></div>   

<?php
    include "invoice_details.php";
       }
   }