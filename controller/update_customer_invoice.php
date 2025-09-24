<?php
session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/update.php";
include "../classes/select.php";
include "../classes/inserts.php";

    if(isset($_SESSION['user_id'])){
            $user = $_SESSION['user_id'];
            $invoice = $_GET['invoice'];
            $payment_type = "Credit";
            $previous_amount = $_SESSION['previous_amount'];
            //get invoice details
            $get_invoice = new selects();
            $details = $get_invoice->fetch_details_cond('sales', 'invoice', $invoice);
            foreach($details as $detail){
                $date = $detail->post_date;
                $customer = $detail->customer;
            }

            //get amount due
            $get_amount_due = new selects();
            $amts = $get_amount_due->fetch_details_cond('customers', 'customer_id', $customer);
            foreach($amts as $amt){
                $amount_due = $amt->amount_due;
                $wallet = $amt->wallet_balance;
            }
           
            
            
        //check if mode is multiple payment

        //update all items with this invoice
        $update_invoice = new Update_table();
        $update_invoice->update_double('sales', 'sales_status', 2, 'post_date', $date, 'invoice', $invoice);
        //update quantity of the items in inventory
       
            if($update_invoice){
                //insert update payment
                //get invoice total amount
                $get_inv_total = new selects();
                $results = $get_inv_total->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                foreach($results as $result){
                    $inv_amount = $result->total;
                }
                //get amount paid
                if($payment_type == "Credit"){
                    $amount_paid = 0;
                }else{
                    $amount_paid = $inv_amount/*  - $discount */;
                }
               
                $update_payment = new Update_table();
                $update_payment->update('payments', 'amount_due', 'invoice', $inv_amount, $invoice);
                
                if($update_payment){
                
                //update debtor list
                if($payment_type == "Credit"){
                    $update_debt_list = new Update_table();
                    $update_debt_list->update('debtors', 'amount', 'invoice', $inv_amount, $invoice);
                    //add funds to debt
                    //remove the oldamount
                    $old_debt = $wallet - intval(-($previous_amount));
                    $new_debt = $old_debt + intval(-($inv_amount));
                    $update_debt = new Update_table();
                    $update_debt->update('customers', 'wallet_balance', 'customer_id', $new_debt, $customer);
                }
                
?>
    <p style="color:#fff; background:green; padding:5px;border-radius:15px; box-shadow:1px 1px 1px #222;text-align:center; width:50%; margin:auto;"><i class="fas fa-thumbs-up"></i> Invoice updated successfully!</p>
<!--  -->
   
<?php
    // echo "<script>window.print();</script>";
                    // }
                }
            }
        
    }else{
        header("Location: ../index.php");
    } 
?>