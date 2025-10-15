<div id="accept_item" class="displays allResults management" style="width:90%!important; margin:10px 50px!important;">
    <a href="javascrit:void(0)" onclick="showPage('receive_po.php')" title="return" style="background:brown; color:#fff; padding:5px; border:1px solid #fff; border-radius:15px; box-shadow:1px 1px 1px #222; margin:5px"><i class="fas fa-angle-double-left"></i> Return</a>

<?php
    session_start();
    $store = $_SESSION['store_id'];
    if(isset($_SESSION['user_id'])){
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
            include "../classes/dbh.php";
            include "../classes/select.php";
            //get invoice details
            $get_details = new selects();
            $rows = $get_details->fetch_details_cond('purchase_order', 'invoice', $invoice);
            if(is_array($rows)){
                foreach($rows as $row){
                    $vendor = $row->vendor;
                }
                //get vendor name
                $vens = $get_details->fetch_details_group('vendors', 'vendor', 'vendor_id', $vendor);
                $vendor_name = $vens->vendor;
            }else{
                $vendor_name = "N/A";
            }

?>


    <div class="info"></div>
    <h2>List of items ordered from <?php echo $vendor_name?> on invoice => <?php echo $invoice?></h2>
    <hr>
    <div class="search">
        <input type="search" id="searchCheckout" placeholder="Enter keyword" onkeyup="searchData(this.value)">
        <a class="download_excel" href="javascript:void(0)" onclick="convertToExcel('data_table', 'Purchase order from <?php echo $vendor_name?> (<?php echo $invoice?>)')"title="Download to excel"><i class="fas fa-file-excel"></i></a>
    </div>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item name</td>
                <td>Qty Ordered</td>
                <td>Unit cost</td>
                <td>Total cost</td>
                <td>Qty Supplied</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $get_items = new selects();
                $details = $get_items->fetch_details_3cond('purchase_order', 'vendor', 'invoice', 'delivery_status', $vendor, $invoice, 0);
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
                    <a style="color:green; background:green; padding:5px 8px; border-radius:5px; color:#fff" href="javascript:void(0)" title="Accept item" onclick="getForm('<?php echo $detail->purchase_id?>', 'get_po_item.php')"> <i class="fas fa-check"></i></a>
                </td>
                
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
        }
    }else{
        echo "Your session has expired! Please login again to continue";
    }
?>
</div>

<script src="../jquery.js"></script>
<script src="../script.js"></script>