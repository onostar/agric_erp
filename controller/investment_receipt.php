<?php
include "field_receipt_style.php";
include "../classes/dbh.php";
include "../classes/select.php";
session_start();

if(isset($_GET['receipt'])){

    $user = $_SESSION['user_id'];
    $invoice = $_GET['receipt'];
    $type = "Investment Payment Receipt";
    
    $get_details = new selects();

    /* -------------------------------------------------
        Fetch payment details
    ------------------------------------------------- */
    $payment_rows = $get_details->fetch_details_cond('investment_payments', 'invoice', $invoice);
    foreach($payment_rows as $pay){
        $customer = $pay->customer;
        $pay_mode = $pay->payment_mode;
        $paid_date = $pay->trx_date;
        $post_date = $pay->post_date;
        $amount = $pay->amount;
        $amount_naira = $pay->amount_in_naira;
        $investment = $pay->investment;
        $store = $pay->store;
    }

    /* -------------------------------------------------
        Fetch customer details
    ------------------------------------------------- */
    $customers = $get_details->fetch_details_cond('customers', 'customer_id', $customer);
    foreach($customers as $cust){
        $account = $cust->acn;
        $customer_name = $cust->customer_name ?? $cust->full_name ?? "Customer";
    }

    /* -------------------------------------------------
        Fetch store details
    ------------------------------------------------- */
    $stores = $get_details->fetch_details_cond('stores', 'store_id', $store);
    foreach($stores as $str){
        $store_name = $str->store;
        $address = $str->store_address;
        $phone = $str->phone_number;
    }

    /* -------------------------------------------------
        Fetch investment details
    ------------------------------------------------- */
    $assigned = $get_details->fetch_details_cond('investments', 'investment_id', $investment);
    foreach($assigned as $asf){
        $invest_amount = $asf->amount;
        $currency = $asf->currency;
        $exchange_rate = $asf->exchange_rate;
        $total_in_naira = $asf->total_in_naira;
    }

    /* -------------------------------------------------
        Total Paid So Far (up to this date)
    ------------------------------------------------- */
    $total_paid_query = $get_details->fetch_sum_date_range('investment_payments', 'amount_in_naira','investment', $investment, 'post_date', $post_date);

    $total_paid = (is_array($total_paid_query)) ? $total_paid_query[0]->total : 0;

    /* -------------------------------------------------
        Calculate Outstanding Balance
    ------------------------------------------------- */
    $balance = $total_in_naira - $total_paid;
?>

<div class="sales_receipt">
    <div class='receipt_logo'>
        <img src="../images/<?php echo $_SESSION['company_logo'];?>" title="logo">
    </div>
    <div class="logo_details">
        <h2><?php echo $_SESSION['company'];?></h2>
        <p><?php echo $address?></p>
        <p>Tel: <?php echo $phone?></p>
    </div>
</div>

<div class="receipt_title">
    <h2 style="text-align:center;margin-top:10px;"><?php echo $type; ?></h2>
</div>

<div class="receipt_section">
    <h4>Transaction Information</h4>
    <p><strong>Invoice No:</strong> <?php echo $invoice; ?></p>
    <p><strong>Payment Date:</strong> <?php echo date("j M Y, g:ia", strtotime($paid_date)); ?></p>
    <p><strong>Posted Date:</strong> <?php echo date("j M Y, g:ia", strtotime($post_date)); ?></p>
    <p><strong>Payment Mode:</strong> <?php echo strtoupper($pay_mode); ?></p>
</div>

<div class="receipt_section">
    <h4>Customer Information</h4>
    <p><strong>Name:</strong> <?php echo $customer_name; ?></p>
    <p><strong>Account No:</strong> <?php echo $account; ?></p>
</div>

<div class="receipt_section">
    <h4>Investment Information</h4>
    <p><strong>Investment ID:</strong> DAV/CON/<?php echo $investment; ?></p>
    <p><strong>Investment Amount:</strong> <?php echo $currency . " " . number_format($invest_amount); ?></p>
    <?php if($currency == "Dollar"){?>
    <p><strong>Exchange Rate:</strong> ₦<?php echo number_format($exchange_rate); ?>/$1.00</p>
    <?php }?>
    <p><strong>Total Value in Naira:</strong> ₦<?php echo number_format($total_in_naira); ?></p>
</div>

<table id="postsales_table" class="searchTable">
    <thead>
        <tr style="background:var(--moreColor)">
            <td>Description</td>
            <td>Amount (₦)</td>
        </tr>
    </thead>
    <tbody>
        <?php
            if($currency == "Dollar"){
                $icon = "$";
            }else{
                $icon = "₦";
            }
        ?>
        <tr>
            <td>Deposit for Investment (<?php echo $icon. number_format($invest_amount); ?>)</td>
            <td><?php echo number_format($amount_naira); ?></td>
        </tr>
    </tbody>
</table>

<div class="receipt_section">
    <h4>Payment Summary</h4>
    <p><strong>Amount Paid Now:</strong> ₦<?php echo number_format($amount_naira); ?></p>
    <p><strong>Total Paid So Far:</strong> ₦<?php echo number_format($total_paid); ?></p>
    <p><strong>Outstanding Balance:</strong> ₦<?php echo number_format($balance); ?></p>
</div>

<div class="receipt_section">
    <?php
        $get_seller = new selects();
        $seller = $get_seller->fetch_details_group('users', 'full_name', 'user_id', $user);
        echo "<p><strong>Posted by:</strong> $seller->full_name</p>";
    ?>
</div>

<p style="text-align:center;margin-top:20px;"><strong>Thank you for your investment!</strong></p>

</div>

<?php
    echo "<script>window.print();</script>";
}
?>
