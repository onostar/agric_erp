
<?php
    include "receipt_style.php";
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_GET['receipt'])){
        $user = $_SESSION['user_id'];
        $invoice = $_GET['receipt'];
        //get store
        
        //get store name
        $get_store_name = new selects();
        $strss = $get_store_name->fetch_details_cond('stores', 'store_id', $store);
        foreach($strss as $strs){
            $store_name = $strs->store;
            $address = $strs->store_address;
            $phone = $strs->phone_number;

        }
        //get details
        $get_payment = new selects();
        $payments = $get_payment->fetch_details_cond('invoices', 'invoice', $invoice);
        foreach($payments as $payment){
            // $pay_mode = $payment->payment_mode;
            $customer = $payment->customer;
            $posted_by = $payment->posted_by;
            $paid_date = $payment->post_date;

        }
    //get customer details
    $get_customer = new selects();
    $custs = $get_customer->fetch_details_cond('customers', 'customer_id', $customer);
    foreach($custs as $cust){
        $full_name = $cust->customer;
        /* $date = new DateTime($cust->dob);
        $now = new DateTime();
        $interval = $now->diff($date);
        $age = $interval->y; */
        $address = $cust->customer_address;
    }   
?>
<div class="displays allResults sales_receipt">
    <?php include "invoice_header.php"?>
        
        
    </div>
    <div class="patient_details">
        <p><strong><span><?php echo $full_name?></span></strong></p>
        <p><strong><span><?php echo $address?></span></strong></p>
        <!-- <p><strong>Invoice Date:</strong> <span><?php echo date("d-m-Y", strtotime($paid_date))?></span></p> -->

    </div>
    <table id="postsales_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td style="font-size:.8rem">S/N</td>
                <td style="font-size:.8rem">Details</td>
                <td style="font-size:.8rem">Agreed Amount (NGN)</td>
                
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_cond('invoices','invoice', $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr style="font-size:.8rem">
                <td style="text-align:center; color:red; font-size:.8rem"><?php echo $n?></td>
                
                <td style="font-size:.8rem"><?php echo $detail->details?>
                <td style="color:var(--moreClor); font-size:.8rem">
                    <?php
                        
                        echo number_format($detail->amount, 2);
                    ?>
                </td>
                
                
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>

    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
        // get sum
        $get_amount = new selects();
        $rows = $get_amount->fetch_sum_single('invoices', 'amount', 'invoice', $invoice);
        foreach($rows as $row){
            $total_amount = $row->total;
       
            //amount paid
            echo "<p class='total_amount' style='color:green'>Amount Due: ₦".number_format($total_amount, 2)."</p>";
            //discount
            /* echo "<p class='total_amount' style='color:green'>Total Discount: ₦".number_format($discount, 2)."</p>"; */ 
            //balance
            /* echo "<p class='total_amount' style='color:green'>Debit Balance: ₦".number_format($balance, 2)."</p>"; */
        }
        //sold by
        $get_seller = new selects();
        $row = $get_seller->fetch_details_group('users', 'full_name', 'user_id', $posted_by);
        echo ucwords("<p class='sold_by'>Prepared by: <strong>$row->full_name</strong></p>");
    ?>
    <p style="margin-top:20px;text-align:center"><strong>Thanks for your patronage!</strong></p>
    <p><strong>Terms and Conditions:</strong></p>

</div> 
   
<?php
    echo "<script>window.print();
    window.close();</script>";
                    // }
                }
            // }
        
    // }
?>