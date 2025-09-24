
<?php
    include "receipt_style.php";
// session_start();
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
    session_start();
    if(isset($_GET['receipt'])){
        $user = $_SESSION['user_id'];
        $invoice = $_GET['receipt'];
        //get store
        $get_store = new selects();
        $str = $get_store->fetch_details_group('sales', 'store', 'invoice', $invoice);
        //get store name
        $get_store_name = new selects();
        $strss = $get_store_name->fetch_details_cond('stores', 'store_id', $str->store);
        foreach($strss as $strs){
            $store_name = $strs->store;
            $address = $strs->store_address;
            $phone = $strs->phone_number;

        }
        //get payment details
        $get_payment = new selects();
        $payments = $get_payment->fetch_details_cond('payments', 'invoice', $invoice);
        foreach($payments as $payment){
            $pay_mode = $payment->payment_mode;
            $customer = $payment->customer;
            $type = $payment->sales_type;
            $paid_date = $payment->post_date;

        }
        //check if payment mode is multiple
        $get_multiples = new selects();
        $multi = $get_multiples->fetch_count_cond('payments', 'invoice', $invoice);  
                
?>
<div class="sales_receipt">
    <?php include "receipt_header.php"?>
        <!-- <p><strong>(<?php echo strtoupper($pay_mode)?> Receipt)</strong></p> -->
        <?php if($multi > 1){?>
        <p><strong>(MULTIPLE)</strong></p>
        <?php }else{?>
        <p><strong>(<?php echo strtoupper($pay_mode)?>)</strong></p>
        <?php }?>
    </div>
    <table id="postsales_table" class="searchTable" style="border-collapse:collapse">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Qty</td>
                <td>Rate</td>
                <td>Amount</td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_cond('sales','invoice', $invoice);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr style="border:1px solid #222">
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td style="color:var(--moreClor);font-size:.8rem">
                    <?php
                        //get category name
                        $get_item_name = new selects();
                        $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $item_name->item_name;
                    ?>
                </td>
                <td style="text-align:center; color:red;font-size:.8rem"><?php echo $detail->quantity?>kg
                    
                </td>
                <td style="font-size:.8rem">
                    <?php 
                        echo number_format($detail->price);
                    ?>
                </td>
                <td style="font-size:.8rem">
                    <?php 
                        echo number_format($detail->total_amount);
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
        // get sum;
        $get_total = new selects();
        $amounts = $get_total->fetch_sum_con('sales', 'price', 'quantity', 'invoice', $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }

        // get amount paid from payments;
        $get_paid = new selects();
        $amt_paids = $get_paid->fetch_sum_single('payments', 'amount_paid', 'invoice', $invoice);
        foreach($amt_paids as $amt){
            $amount_paid = $amt->total;
        }
        //get discount
        $get_discount = new selects();
        $discs = $get_discount->fetch_sum_2colCond('sales', 'quantity', 'discount', 'invoice', $invoice);
        foreach($discs as $disc){
            $discount = $disc->total;
        }
        //amount due
        echo "<p class='total_amount' style='color:#000'>Amount due: ₦".number_format($total_amount, 2)."</p>";
        //check if payment mode is multiple
        $get_multiple = new selects();
        $multis = $get_multiple->fetch_count_cond('payments', 'invoice', $invoice);
        if($multis > 1){
            $rows = $get_paid->fetch_details_cond('payments', 'invoice', $invoice);
            foreach($rows as $row){
                $amount_paid = $row->amount_paid;
                $amount_due = $row->amount_due;
                $mode = $row->payment_mode;
                // $discount = $row->discount;
                $balance = $amount_due - $amount_paid;
                
                //amount paid
                echo "<p class='total_amount' style='color:#000'>".$mode.": ₦".number_format($amount_paid, 2)."</p>";
                if($discount != 0){
                    echo "<p class='total_amount' style='color:green'>Discount: ₦".number_format($discount, 2)."</p>";
    
                }
            }
        }else{
            $rows = $get_paid->fetch_details_cond('payments', 'invoice', $invoice);
            foreach($rows as $row){
                $amount_paid = $row->amount_paid;
                $amount_due = $row->amount_due;
                // $discount = $row->discount;
                $balance = $amount_due - $amount_paid;
                //amount paid
                echo "<p class='total_amount' style='color:#000'>Amount paid: ₦".number_format($amount_paid, 2)."</p>";
                if($discount != 0){
                    echo "<p class='total_amount' style='color:green'>Discount: ₦".number_format($discount, 2)."</p>";
    
                }
            }
            //balance
            echo "<p class='total_amount' style='color:green'>Balance: ₦".number_format($balance, 2)."</p>";
        }
        
        // get sum
        /* $get_total = new selects();
        $amounts = $get_total->fetch_sum_con('sales', 'price', 'quantity', 'invoice', $invoice);
        foreach($amounts as $amount){
            $total_amount = $amount->total;
        }
        // get amount paid from payments;
        $get_paid = new selects();
        $amt_paids = $get_paid->fetch_sum_single('payments', 'amount_paid', 'invoice', $invoice);
        foreach($amt_paids as $amt){
            $amount_paid = $amt->total;
        }
        //get discount
        $get_discount = new selects();
        $discs = $get_discount->fetch_sum_2colCond('sales', 'quantity', 'discount', 'invoice', $invoice);
        foreach($discs as $disc){
            $discount = $disc->total;
        }
        $rows = $get_paid->fetch_details_cond('payments', 'invoice', $invoice);
        foreach($rows as $row){
            $amount_paid = $row->amount_paid;
            $amount_due = $row->amount_due;
            // $discount = $row->discount;
            $balance = $amount_due - $amount_paid;
            //amount due
            echo "<p class='total_amount' style='color:green'>AMount Due: ₦".number_format($amount_due, 2)."</p>";
            //amount paid
            echo "<p class='total_amount' style='color:green'>Amount Paid: ₦".number_format($amount_paid, 2)."</p>";
            //discount
            echo "<p class='total_amount' style='color:green'>Total Discount: ₦".number_format($discount, 2)."</p>"; 
            //balance
            echo "<p class='total_amount' style='color:green'>Balance: ₦".number_format($balance, 2)."</p>";
        } */
        //sold by
        $get_seller = new selects();
        $row = $get_seller->fetch_details_group('users', 'full_name', 'user_id', $user);
        echo ucwords("<p class='sold_by'>Sold by: <strong>$row->full_name</strong></p>");
    ?>
    <div class="receive" style="width:100%; display:flex;align-items:center;justify-content:space-between;margin-top:20px">
        <div class="rec" style="width:60%;border-top:2px dotted #222">
            <p>Received by</p>
        </div>
        <div class="rec" style="width:35%;border-top:2px dotted #222">
            <p>Date</p>
        </div>
    </div>
    
    <p style="margin-top:10px;text-align:center"><strong>Thanks for your patronage!</strong></p>
</div> 
   
<?php
    echo "<script>window.print();
    window.close();</script>
    ";
                    // }
                }
            // }
        
    // }
?>