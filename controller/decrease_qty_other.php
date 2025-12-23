<?php
    session_start();

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
            $customer = $row->customer;
            $sales_qty = $row->quantity;
        }
        // check item current quantity in sales order
        /* $check_salesqty = new selects();
        $qtys = $check_salesqty->fetch_details_group('sales', 'quantity', 'sales_id', $item);
        $sales_qty = $qtys->quantity; */
        if($sales_qty == 1){
            echo "<script>alert('Cannot reduce item quantity to zero or negative value!');
            </script>";
?>
<!-- display items with same invoice number -->

<?php
    include "other_sales_details.php";
        }else{
        
        //update quantity
        $update = new Update_table();
        $update->decrease_qty(1, $item);
        if($update){
            //update total amount
            // check item new quantity in sales order
            $shows = $get_invoice->fetch_details_cond('sales', 'sales_id', $item);
            foreach($shows as $show){
                $new_qty = $show->quantity;
                $unit_price = $show->price;
                $item_id = $show->item;
                $store = $show->store;
                
            }
            //get cost price from inventory
            $costs = $get_invoice->fetch_details_2cond('prices', 'store', 'item', $item, $store);
            if(is_array($costs)){
                foreach($costs as $cos){
                    $cost_price = $cos->cost;
                }
            }else{
                $cost_price = 0;
            }
            $total_price = $new_qty * $unit_price;
            $total_cost = $new_qty * $cost_price;
            $update_total = new Update_table();
            $update_total->update_double('sales', 'total_amount', $total_price, 'cost', $total_cost, 'sales_id', $item);
            if($update_total){
?>
<!-- display items with same invoice number -->
    
<?php
    include "other_sales_details.php";
            }            
        }
    }
?>