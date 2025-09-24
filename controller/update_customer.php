<?php
    $customer_id = htmlspecialchars(stripslashes($_POST['customer_id']));
    $customer = ucwords(htmlspecialchars(stripslashes($_POST['customer'])));
    $phone = htmlspecialchars(stripslashes($_POST['phone_number']));
    $address = ucwords(htmlspecialchars(stripslashes(($_POST['address']))));
    $email = htmlspecialchars(stripslashes(($_POST['email'])));

   
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/update.php";

       //update customer
       $update_data = new Update_table();
       $update_data->update_quadruple('customers', 'customer', $customer, 'phone_numbers',$phone, 'customer_address', $address, 'customer_email', $email, 'customer_id', $customer_id);
       if($update_data){
           echo "<div class='success'><p>$customer</span> details updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
       }
   