
<div class='receipt_logo'><img src="../images/com_logo.jpg" title="logo"></div>
    <h2><?php echo $_SESSION['company'];?></h2>
    <p><?php echo $address?></p>
    <p>Tel: <?php echo $phone?></p>
    <!-- get sales type -->
    <?php 
        
    ?>
        <p>Invoice Date: <?php echo date("d-m-Y", strtotime($paid_date))?></p>

    <div class="receipt_head">
        <p>Invoice No.: <?php echo $invoice?></p>
        
