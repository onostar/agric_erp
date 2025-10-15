<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
            //get invoice details
            $get_details = new selects();
            $rows = $get_details->fetch_details_cond('purchase_order', 'invoice', $invoice);
            if(is_array($rows)){
                foreach($rows as $row){
                    $store = $row->store;
                    $supplier = $row->vendor;
                }
            }
            //get vendor name
            $vend = $get_details->fetch_details_group('vendors', 'vendor', 'vendor_id',$supplier);
            $vendor = $vend->vendor;
        // $_SESSION['invoice'] = $invoice;
    
?>

<div id="stockin">
    <div class="displays allResults" id="stocked_items" style="width:80%!important; margin:50px!important">
        <a href="javascrit:void(0)" onclick="showPage('purchase_order_report.php')" title="return" style="background:brown; color:#fff; padding:5px; border:1px solid #fff; border-radius:15px; box-shadow:1px 1px 1px #222; margin:5px"><i class="fas fa-angle-double-left"></i> Return</a>
        <h2 style="font-size:1rem; color:var(--tertiaryColor)">Items on Purchase order => <?php echo $invoice?> (Vendor: <?php echo $vendor?>)</h2>
        <table id="stock_items_table" class="searchTable">
            <thead>
                <tr style="background:var(--moreColor)">
                    <td>S/N</td>
                    <td>Item name</td>
                    <td>Quantity</td>
                    <td>Unit cost</td>
                    <td>Total cost</td>
                    <td>Qty Supplied</td>
                    <td>Status</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    $get_items = new selects();
                    $details = $get_items->fetch_details_2cond('purchase_order', 'vendor', 'invoice', $supplier, $invoice);
                    if(gettype($details) === 'array'){
                    foreach($details as $detail):
                ?>
                <tr>
                    <td style="text-align:center; color:red;"><?php echo $n?></td>
                    <td style="color:var(--moreClor);">
                        <?php
                            //get category name
                            $get_item_name = new selects();
                            $item_name = $get_item_name->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                            echo $item_name->item_name;
                        ?>
                    </td>
                    <td style="text-align:center"><?php echo $detail->quantity?></td>
                    <td>
                        <?php 
                            echo "₦".number_format($detail->cost_price, 2);
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo "₦".number_format($detail->cost_price * $detail->quantity, 2);
                        ?>
                    </td>
                    <td style="text-align:center; color:green"><?php echo $detail->supplied?></td>
                    <td>
                        <?php
                            //check status
                            if($detail->delivery_status == 0){
                                echo "<span style='color:var(--secondaryColor)'> Pending <i class='fas fa-spinner'></i></span>";
                            }else{
                                echo "<span style='color:green'> Delivered <i class='fas fa-check'></i></span>";

                            }
                        ?>
                    </td>
                    <td><a href="javascript:void(0)" onclick="viewDelivery('<?php echo $detail->purchase_id?>')"title="view delivery details" style="background:var(--otherColor); color:#fff; padding:5px; box-shadow:1px 1px 1px #222; border:1px solid #fff; border-radius:15px;">View <i class="fas fa-eye"></i></a></td>
                </tr>
                
                <?php $n++; endforeach;}?>
            </tbody>
        </table>

        
        <?php
            if(gettype($details) == "string"){
                echo "<p class='no_result'>'$details'</p>";
            }

            
        ?>
    <?php
            if(is_array($details)){
                // get sum
            $get_total = new selects();
            $amounts = $get_total->fetch_sum_2con('purchase_order', 'cost_price', 'quantity', 'vendor', 'invoice', $supplier, $invoice);
            foreach($amounts as $amount){
                $total_amount = $amount->total;
            }
            // $total_worth = $total_amount * $total_qty;
            echo "<p class='total_amount' style='color:red;float:right'>Total Cost: ₦".number_format($total_amount, 2)."</p>";
        ?>
       
        <?php }?>
    </div>
    <div id="delivery">

    </div>
</div>
<?php
        }
    }else{
        echo "Your Session has expired. Please Login again to continue";
        exit();
        // header("Location: ../index.php");
    }
?>