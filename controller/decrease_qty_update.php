<?php
    session_start();
        $store = $_SESSION['store_id'];
        $item = $_GET['item'];
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('sales', 'sales_id', $item);
        foreach($rows as $row){
            $invoice = $row->invoice;
            $item_id = $row->item;
        }

        // check item current quantity in inventory
        $check_qty = new selects();
        $qtys = $check_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item_id);
        foreach($qtys as $qty){
            $current_qty = $qty->quantity;

        }
        // check item current quantity in sales order
        $check_salesqty = new selects();
        $qtys = $check_salesqty->fetch_details_group('sales', 'quantity', 'sales_id', $item);
        $sales_qty = $qtys->quantity;
        if($sales_qty == 1){
            echo "<script>alert('Cannot reduce item quantity to zero or negative value!');
            </script>";
?>
<!-- display items with same invoice number -->

<?php
    include "update_details.php";
        }else{
        
        //update quantity
        $update = new Update_table();
        $update->decrease_qty(1, $item);
        if($update){
            //update total amount
            // check item new quantity in sales order
            $check_itemqty = new selects();
            $shows = $check_itemqty->fetch_details_cond('sales', 'sales_id', $item);
            foreach($shows as $show){
                $new_qty = $show->quantity;
                $unit_price = $show->price;
                $item_id = $show->item;
                
            }
            //get cost price from inventory
            $get_cost = new selects();
            $costs = $get_cost->fetch_details_group('items', 'cost_price', 'item_id', $item_id);
            $cost_price = $costs->cost_price;
            $total_price = $new_qty * $unit_price;
            $total_cost = $new_qty * $cost_price;
            $update_total = new Update_table();
            $update_total->update_double('sales', 'total_amount', $total_price, 'cost', $total_cost, 'sales_id', $item);
            if($update_total){
                //update inventory
            $inv_qty = $current_qty + 1;
            $update_inv = new Update_table();
            $update_inv->update2cond('inventory', 'quantity', 'item', 'store', $inv_qty, $item_id, $store);
?>
<!-- display items with same invoice number -->
    
<?php
    include "update_details.php";
            }            
        }
    }
?>