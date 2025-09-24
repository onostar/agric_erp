<?php
    session_start();
    $user = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];

    if (isset($_GET['item'])){
        $item = $_GET['item'];
    
    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get user role
    /* $get_role = new selects();
    $rowss = $get_role->fetch_details_group('users', 'user_role', 'user_id', $user);
    $role = $rowss->user_role; */

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('invoices', 'invoice_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            $invoice = $row->invoice;
            $amount = $row->amount;
            $details = $row->details;
        }
        
    ?>
    <div class="add_user_form priceForm" style="width:70%!important;margin:0 auto!important">
        
        <section class="addUserForm" style="text-align:left; padding:10px; margin:0; width:100%;">
        <h3 style="background:var(--moreColor);">Edit details for <?php echo strtoupper($invoice)?></h3>
            <div class="inputs">    
                <input type="hidden" name="invoice_id" id="invoice_id" value="<?php echo $item?>" required>
                <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>" required>
                    
                <div class="data">
                    <label for="qty">Amount (NGN)</label>
                    <input type="text" name="amount_update" id="amount_update" value="<?php echo $amount?>">
                </div>
                <div class="data">
                    <label for="price">Details</label>
                    <textarea style="height:auto!important"name="details_update" id="details_update"><?php echo $details?></textarea>
                    
                </div>
                
                <div class="data">
                    <button type="submit" id="change_price" name="change_price" onclick="editDetails()">Update </button>
                    <a href="javascript:void(0)" title="close form" style='background:brown; padding:8px; border-radius:15px; color:#fff' onclick="closeForm()">Close <i class="fas fa-close"></i></a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
     }
    }    
?>