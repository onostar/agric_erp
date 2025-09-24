
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    
    if(isset($_GET['invoice'])){
        $invoice = $_GET['invoice'];
       
        //get invoice details

?>


<div class="displays all_details">
    <!-- <div class="info"></div> -->
    
    <div class="guest_name">
        <h4>Items on Invoice => <?php echo $invoice?> </h4>
        <div class="displays allResults" id="payment_det">
        <!-- <a style="color:#fff;background:brown;padding:5px; margin:5px;border-radius:15px; box-shadow:1px 1px 1px #222; float:right" href="javascript:void(0)" title="update invoice" onclick="showPage('../controller/update_invoices.php?invoice=<?php echo $invoice?>')">Update <i class="fas fa-pen"></i></a> -->
            <div class="payment_details">
                <h3>Invoice Details</h3>
                <table id="guest_payment_table" class="searchTable">
                    <thead>
                        <tr>
                            <td>S/N</td>
                            <td>Item</td>
                            <td>Quantity</td>
                            <td>Unit price</td>
                            <td>Amount</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            $n = 1;
                            $get_items = new selects();
                            $rows = $get_items->fetch_details_cond('sales', 'invoice', $invoice);
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
                                ?>
                            </td>
                            <td style="text-align:center; color:var(--otherColor)"><?php echo $row->quantity?></td>
                            <td><?php echo number_format($row->price, 2);?></td>
                            <td><?php echo number_format($row->total_amount, 2)?></td>
                            
                        </tr>
                        
                        <?php $n++; }?>
                    </tbody>
                </table>
            </div>
            <div class="amount_due">
                <h2>Total Amount: 
                <?php
                    //get total amount
                    $get_total = new selects();
                    $details = $get_total->fetch_sum_single('sales', 'total_amount', 'invoice', $invoice);
                    foreach($details as $detail){
                        $total_amount = $detail->total;
                        echo "₦".number_format($total_amount, 2);
                    }
                    $_SESSION['previous_amount'] = $total_amount;
                ?>

                </h2>

                <p>
                    <?php
                        //get payment status
                        $get_status = new selects();
                        $status = $get_status->fetch_details_cond('debtors', 'invoice', $invoice);
                        if(gettype($status) == 'array'){
                            foreach($status as $stat){
                                $debt = $stat->debt_status;
                            }
                        }
                        if(gettype($status) == 'string'){
                            $debt = 1;
                        }
                        if($debt = 1){
                            echo "<p style='text-align:center!important; width:auto; color:#fff; padding:10px; background:var(--tertiaryColor);font-size:1rem;'>PAID</p>";
                        }else{
                            echo "<p style='text-align:center!important; width:auto; color:#fff; padding:10px; font-size:1rem;background:red'>UNPAID</p>";
                        }
                    ?>
                </p>
            </div>
            
    </div>
    
</div>
<?php
            }
        
   
?>