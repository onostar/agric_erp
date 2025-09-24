
<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    
    if(isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
        $vendor = $_GET['vendor'];
        //get invoice details

?>


<div class="displays all_details">
    <!-- <div class="info"></div> -->
    
    <div class="guest_name">
        <h4>Items on Invoice => <?php echo $invoice?> </h4>
        <div class="displays allResults" id="payment_det">
        
            <div class="payment_details">
                <h3>Invoice Details</h3>
                <table id="guest_payment_table" class="searchTable">
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Item</td>
                            <td>Quantity</td>
                            <td>Unit cost</td>
                            <td>Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $get_items = new selects();
                            $rows = $get_items->fetch_details_2cond('purchases', 'invoice', 'vendor', $invoice, $vendor);
                            foreach($rows as $row){
                        ?>
                        <tr>
                            <td style="text-align:center; color:red;"><?php echo $n?></td>
                            <td>
                                <?php 
                                    //get item name
                                    $get_name = new selects();
                                    $names = $get_name->fetch_details_group('items', 'item_name', 'item_id', $row->item);
                                    echo strtoupper($names->item_name);
                                    // echo $row->details;
                                ?>
                            </td>
                            <td style="text-align:center; color:var(--otherColor)"><?php echo $row->quantity?></td>
                            <td><?php echo number_format($row->cost_price, 2);?></td>
                            <td><?php echo number_format($row->cost_price * $row->quantity, 2)?></td>
                            
                        </tr>
                        
                        <?php $n++; }?>
                    </tbody>
                </table>
            </div>
            <div class="amount_due">
                <p>Invoice Amount: 
                <?php
                    //get total amount
                    $get_total = new selects();
                    $details = $get_total->fetch_sum_2colCond('purchases', 'cost_price', 'quantity', 'invoice', $invoice);
                    foreach($details as $detail){
                        $invoice_amount = $detail->total;
                        echo "₦".number_format($detail->total, 2);
                    }
                ?>
                </p>
                <p>Logistics Amount: 
                <?php
                    //get waybill
                    $get_bill = new selects();
                    $bills = $get_bill->fetch_details_2cond('purchases', 'invoice', 'vendor', $invoice, $vendor);
                    foreach($bills as $bill){
                        $logistic = $bill->waybill;
                    }
                    echo "₦".number_format($logistic, 2);
                ?>
                </p>
                <h2>Total Amount: 
                <?php
                    
                    echo "₦".number_format($logistic + $invoice_amount, 2);
                ?>
                </h2>

                
            </div>
            
    </div>
    
</div>
<?php
            }
        
   
?>