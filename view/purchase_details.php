<div id="post_purchase">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    
    if(isset($_GET['invoice']) && isset($_GET['vendor'])){
        $invoice = $_GET['invoice'];
        $vendor = $_GET['vendor'];
        //get vendor details
        $get_vendor = new selects();
        $vens = $get_vendor->fetch_details_cond('vendors', 'vendor_id', $vendor);
        foreach($vens as $ven){
            $vendor_name = $ven->vendor;
            $vendor_ledger = $ven->ledger_id;
        }
        //get invoice details

?>


<div class="displays all_details" style="width:100%!important">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" style="margin:0 50px"onclick="showPage('post_purchase.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="guest_name">
        <div class="displays allResults" id="payment_det">
            <h3 style="background:var(--otherColor); color:#fff; padding:10px;">POST PURCHASE FOR "<?php echo $vendor_name?>"</h3>
            <p>Invoice #: <?php echo $invoice?></p>
            <div class="payment_detsss">
                <div class="payment_details" style="width:60%;margin-top:0;">
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
                                $rows = $get_items->fetch_details_2cond('purchases', 'invoice','vendor',  $invoice, $vendor);
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
                    <div class="amount_due">
                        <h2>Invoice Amount: 
                            <?php
                                //get total amount
                                $get_total = new selects();
                                $details = $get_total->fetch_sum_2col2Cond('purchases', 'quantity', 'cost_price', 'invoice', $invoice, 'vendor', $vendor);
                                foreach($details as $detail){
                                    $total_amount = $detail->total;
                                    echo "₦".number_format($total_amount, 2);
                                }
                            ?>
                        </h2>
                    </div>
                </div>
                <div class="pay_form" style="width:35%;">
                    
                    <div class="close_stockin add_user_form" style="width:100%; margin:0;">
                        <section class="addUserForm">
                        <div class="inputs" style="display:flex; flex-wrap:wrap; gap:1rem">
                            <div class="data" style="width:100%">
                            <label for="discount" style="color:red;">Waybill/Logistics</label><br>
                            <?php
                                $get_way = new selects();
                                $ways = $get_way->fetch_details_2cond('purchases', 'invoice', 'vendor', $invoice, $vendor);
                                foreach($ways as $way){
                                    $waybill = $way->waybill;
                                }
                            ?>
                                <input type="text" name="waybill" id="waybill" style="padding:10px;border-radius:5px;" value="<?php echo $waybill?>">
                            </div>
                            
                            <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $detail->total?>">
                            <input type="hidden" name="vendor" id="vendor" value="<?php echo $vendor?>">
                            <input type="hidden" name="purchase_invoice" id="purchase_invoice" value="<?php echo $invoice?>">
                            <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                            <div class="data" style="width:100%">
                                <label for="">Total Amount</label>
                                <input type="text" name="invoice_amount" id="invoice_amount" value="<?php echo number_format($total_amount + $waybill, 2)?>" readonly>
                            </div>
                            <div class="data" style="width:100%">
                                <label for="payment_type">Payment options</label>
                                <select name="payment_type" id="payment_type" onchange="checkOtherMode(this.value)">
                                    <option value="" selected>Select payment type</option>
                                    <option value="Full payment">Full payment</option>
                                    <!-- <option value="Deposit">Deposit</option> -->
                                    <!-- <option value="Transfer">TRANSFER</option>-->
                                    <option  value="Credit">Credit</option>

                                </select>
                            </div>
                        
                            <div class="data" id="deposited_amount" style="width:100%">
                                <label for="">Deposit Amount</label>
                                <input type="text" name="deposit_amount" id="deposit_amount" value="0">
                            </div>
                            <div class="data" style="width:100%;" id="cr_ledger">
                                <label for="contra"><span class="ledger">Cr. </span>Ledger</label>
                                <select name="contra" id="contra">
                                    <option value="" selected>Select Credit ledger</option>
                                    <?php
                                        $get_heads = new selects();
                                        $heads = $get_heads->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                                        foreach($heads as $head){
                                    ?>
                                    <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="data">
                                <button onclick="postPurchases()" style="background:green; padding:8px; border-radius:5px;font-size:.9rem;">Post purchase <i class="fas fa-print"></i></button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
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