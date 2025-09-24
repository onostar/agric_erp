<div id="guest_details">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
    
    if(isset($_GET['payment_id'])){
        $payment = $_GET['payment_id'];
        //get invoice;
        $get_invoice = new selects();
        $payment_invoices = $get_invoice->fetch_details_cond('invoices', 'invoice', $payment);
        foreach($payment_invoices as $payment_invoice){
            $customer = $payment_invoice->customer;
            $date = $payment_invoice->post_date;
            $details = $payment_invoice->details;
            $amount = $payment_invoice->amount;
        }
        //get invoice details

?>


<div class="displays all_details">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" onclick="showPage('invoice_reports.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="guest_name">
        <?php
            //get customer name
            $get_cust = new selects();
            $clients = $get_cust->fetch_details_cond('customers', 'customer_id', $customer);
            foreach($clients as $client){
                $name = $client->customer;
                $phone =$client->phone_numbers;
            }
        ?>
        <h4>Invoice for <?php echo strtoupper($name)?> (<?php echo $payment?>)</h4>
        
        <div class="displays allResults" id="payment_det">
        
            <div class="payment_details">
                <h3>Details</h3>
                <table id="guest_payment_table" class="searchTable">
                <thead>
                    <tr style="background:var(--moreColor)">
                        <td>S/N</td>
                        <td>Items</td>
                        <td>Amount</td>
                        <!-- <td></td> -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                        $n = 1;
                        $get_items = new selects();
                        $details = $get_items->fetch_details_cond('invoices','invoice', $payment);
                        if(gettype($details) === 'array'){
                        foreach($details as $detail):
                    ?>
                    <tr>
                        <td style="text-align:center; color:red;"><?php echo $n?></td>
                        
                        <td>
                            <?php
                                
                                echo $detail->details;
                            ?>
                        </td>
                        <td style="color:green">
                            <?php
                                
                                echo number_format($detail->amount, 2);
                            ?>
                        </td>
                        
                    </tr>
                    
                    <?php $n++; endforeach;}?>
                </tbody>
                </table>
            </div>
            
        <div class="all_modes">
           
            <a href="javascript:void(0)" class="sum_amount" style="background:var(--moreColor)"onclick="printInvoice('<?php echo $payment?>')">Print <i class="fas fa-print"></i></a>
            <!-- <a href="javascript:void(0)" class="sum_amount" style="background:var(--secondaryColor)"onclick="sendMail('<?php echo $payment?>')">Send to email <i class="fas fa-envelope"></i></a> -->
            <!-- <a href="https://wa.me/+234<?php echo $phone?>?text=Your%20prescription:%20for%20<?php echo date("jS M, Y", strtotime($date))?>%20:%20<?php echo $prescriptions?>" class="sum_amount" target="_blank"style="background:green">Whatsapp <i class="fab fa-whatsapp"></i></a> -->
        </div>
    </div>
    
</div>
<?php
            }
        
    }else{
        header("Location: ../index.php");
    }
?>
</div>