<?php
    session_start();
    $user = $_SESSION['user_id'];
    $store = $_SESSION['store_id'];

    if (isset($_GET['item'])){
        $sales = $_GET['item'];
    

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    //get user role
    $get_role = new selects();
    $rowss = $get_role->fetch_details_group('users', 'user_role', 'user_id', $user);
    $role = $rowss->user_role;

    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('sales', 'sales_id', $sales);
     if(gettype($rows) == 'array'){
        foreach($rows as $row):
        //get item details from item table
        $get_item = new selects();
        $details = $get_item->fetch_details_cond('items', 'item_id', $row->item);
        foreach($details as $detail){
            $name = $detail->item_name;
        }
        //get item quantity from store
        $get_qty = new selects();
        $qtys = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $row->item);
        foreach($qtys as $qty){
            $item_qty = $qty->quantity;

        }
    ?>
    <div class="add_user_form" style="width:90%; padding:0!important">
        
        <section style="text-align:left; padding:0 0 5px 0; margin:0; width:100%;">
        <h3 style="background:var(--tertiaryColor);">Edit quantity and price for <?php echo strtoupper($name)?></h3>
            <div class="inputs" style="justify-content:left">
                <div class="data item_head" style="width:auto;background:green">
                    <?php if($name == "CONCENTRATE"){?>
                    <h4 title="available quantity">Avai. Qty: <?php echo $item_qty?>Ltr</h4>
                    <?php } else { ?>
                    <h4 title="available quantity">Avai. Qty: <?php echo $item_qty?>kg</h4>
                    <?php } ?>
                    <input type="hidden" name="sales_id" id="sales_id" value="<?php echo $row->sales_id?>" required>
                    <input type="hidden" name="inv_qty" id="inv_qty" value="<?php echo $item_qty?>" required>
                </div>
                <div class="data" style="width:18%">
                    <?php
                        if($name == "CONCENTRATE"){
                    ?>
                    <label for="qty">Qty (Ltr)</label>
                    <?php } else { ?>
                    <label for="qty">Qty (kg)</label>
                    <?php } ?>
                    <input type="text" name="qty" id="qty" value="<?php echo $row->quantity?>">
                </div>
                <div class="data" style="width:18%">
                    <label for="price">Unit price (NGN)</label>
                    <input type="text" name="price" id="price" value="<?php echo $row->price?>">
                    
                </div>

                <div class="data" style="width:18%">
                    <label for="total_amount">Total Amount (NGN)</label>
                    <input type="text" name="total_amount" id="total_amount" value="<?php echo $row->total_amount?>" readonly>
                </div>
                <div class="data" style="width:auto">
                    <button type="submit" id="change_price" name="change_price" onclick="updatePriceQtyWh()">Update </button>
                    <a href="javascript:void(0)" title="close form" style='background:red; padding:10px; border-radius:5px; color:#fff' onclick="closeForm()">Return</a>
                </div>
            </div>
        </section>   
    </div>
    
<?php
    endforeach;
     }
    }    
?>