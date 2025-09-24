<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['invoice'])){
            $customer = $_SESSION['customer'];
            $invoice = $_GET['invoice'];
            //get invoice details
            $get_details = new selects();
            $details = $get_details->fetch_details_cond('sales', 'invoice', $invoice);
            foreach($details as $detail){
                $customer = $detail->customer;
            }
            //get customer name
            $get_customer = new selects();
            $rows = $get_customer->fetch_details_group('customers', 'customer', 'customer_id', $customer);
            $customer_name = $rows->customer;

?>
<div class="close_btn">
    <a href="javascript:void(0)" title="Return to statement" onclick="showPage('../controller/customer_statement.php?customer=<?php echo $customer?>');" class="close_form">Return <i class="fas fa-arrow-rotate-left"></i></a>
</div>
<div id="direct_sales">
<div id="sales_form" class="displays all_details">
    
    
    <div class="add_user_form" style="width:50%; margin:10px 0; box-shadow:none">
        <h3 style="background:var(--primaryColor); color:#ff; text-align:left!important;" >Sales order for <?php echo $customer_name. " (".$invoice.")"?></h3>
        
            <!-- search forms -->
        <!-- <form method="POST" id="addUserForm"> -->
            <section class="addUserForm">
                <div class="inputs">
                    <!-- bar items form -->
                    <div class="data" id="bar_items" style="width:100%; margin:2px 0">
                        <label for="item"> Search Items</label>
                        <input type="hidden" name="customer" id="customer" value="<?php echo $customer?>">
                        <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>">
                        <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getUpdateItems(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    </div>
                    
                </div>
            </section>
            
        </div>
    </div>

</div>
<!-- for editing item quantitiy and price -->
<div class="show_more"></div>
<!-- showing all items in the sales order -->
<div class="sales_order">
    
    <div class="displays allResults" id="stocked_items">
    <!-- <h2>Items in sales order</h2> -->
        <table id="addsales_table" class="searchTable">
            <thead>
                <tr style="background:var(--moreColor)">
                    <td>S/N</td>
                    <td>Item name</td>
                    <td>Quantity</td>
                    <td>Unit sales</td>
                    <td>Amount</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                <?php
                    $n = 1;
                    if(gettype($details) == 'array'){
                        foreach($details as $detail){
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
                    <td style="text-align:center; color:red;font-size:1,1rem">
                        <span style="font-size:1.2rem; margin:0 2px"><?php echo $detail->quantity?></span>
                        <a style="color:#fff; background:green;border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="increase quantity" onclick="increaseQtyUpdate('<?php echo $detail->sales_id?>', '<?php echo $detail->item?>')"><i class="fas fa-arrow-up"></i></a>
                        <a style="color:#fff; background:var(--primaryColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="decrease quantity" onclick="reduceQtyUpdate('<?php echo $detail->sales_id?>')"><i class="fas fa-arrow-down"></i></a>
                        <a style="color:#fff; background:var(--otherColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="show more options" onclick="showMoreUpdate('<?php echo $detail->sales_id?>')"><i class="fas fa-pen"></i></a>
                        <!-- <a style="color:#fff; background:var(--secondaryColor);border-radius:4px;padding:5px 8px;" href="javascript:void(0)" title="sell item in pack" onclick="getWholesalePack('<?php echo $detail->sales_id?>')"><i class="fas fa-box"></i> pack</a> -->
                    </td>
                    <td>
                        <?php 
                            echo "₦".number_format($detail->price, 2);
                        ?>
                    </td>
                    <td>
                        <?php 
                            echo "₦".number_format($detail->total_amount, 2);
                        ?>
                    </td>
                    <td>
                        <a style="color:red; font-size:1rem" href="javascript:void(0) "title="delete item" onclick="deleteUpdate('<?php echo $detail->sales_id?>', '<?php echo $detail->item?>')"><i class="fas fa-trash"></i></a>
                    </td>
                    
                </tr>
                
                <?php $n++; }}?>
            </tbody>
        </table>
        <!-- <div class="data">
            <button onclick="updateInvoice()" style="background:green; padding:5px; border-radius:15px;font-size:.9rem;">Save <i class="fas fa-save"></i></button>
        </div> -->
    </div>
</div>
<?php
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>