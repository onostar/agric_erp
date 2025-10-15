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
    <div class="displays">
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--primaryColor); text-align:left!important;" >Update Purchase Order for <?php echo $vendor?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data">
                    <label for="vendor">Supplier</label>
                    <input type="hidden" id="invoice" name="invoice" value="<?php echo $invoice?>">
                    <input type="text" name="supplier" id="supplier" required value="<?php echo $vendor?>" onkeyup="getPOSupplier(this.value)">
                    <input type="hidden" name="vendor" id="vendor" value="<?php echo $supplier?>">
                        <div id="transfer_item">
                            
                        </div>
                </div>
                <div class="data" style="width:100%; margin:10px 0">
                    <input type="text" name="item" id="item" required placeholder="Input item name or barcode" onkeyup="getItemPO(this.value)">
                        <div id="sales_item">
                            
                        </div>
                    
                </div>
            </div>
        </section>
    </div>
    <div class="info" style="width:100%; margin:0"></div>
    <div class="stocked_in">
        <?php include "../controller/purchase_order_details.php"?>
    </div>
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