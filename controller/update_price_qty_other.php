<?php
        session_start();
        $store = $_SESSION['store_id'];
        $sales = htmlspecialchars(stripslashes($_POST['sales_id']));
        $qty = htmlspecialchars(stripslashes($_POST['qty']));
        $price = htmlspecialchars(stripslashes($_POST['price']));
        $new_amount = $qty * $price;

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/select.php";
        include "../classes/update.php";
        //get invoice
        $get_invoice = new selects();
        $rows = $get_invoice->fetch_details_cond('sales', 'sales_id', $sales);
        foreach($rows as $row){
            $invoice = $row->invoice;
            $customer = $row->customer;
            $item_id = $row->item;
        }
        //get item quantity from inventory
        //first get the item from sales order
        /* $get_item = new selects();
        $item = $get_item->fetch_details_group('sales', 'item', 'sales_id', $sales);
        $item_id = $item->item; */
        //get item price
        // $get_price = new selects();
        $unit_price = $get_invoice->fetch_details_2cond('prices', 'item', 'store', $item_id, $store);
        if(is_array($unit_price)){
            foreach($unit_price as $up){
                $old_price = $up->other_price;
                $cost_price = $up->cost;
            }
        }else{
            $old_price = 0;
            $cost_price = 0;
        }
        $discount = $old_price - $price;
        
        $item_qtys = $get_invoice->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $item_id);
        foreach($item_qtys as $item_qty){
            $inv_qty = $item_qty->total;
        }
        if($qty > $inv_qty){
            echo "<script>alert('Available Quantity is less than required! Can not proceed!')</script>";
        }else{
            
            $total_cost = $qty * $cost_price;
            if($cost_price > $price){
                echo "<script>alert('Sales price cannot be less than cost price!')</script>";
            }else{
                //update quantity and price
                $update = new Update_table();
                $update->update_multiple('sales', 'quantity', $qty, 'price', $price, 'total_amount', $new_amount, 'discount', $discount, 'cost', $total_cost, 'sales_id', $sales);
                /* $update_cost = new Update_table();
                $update_cost->update('sales', 'cost', 'sales_id', $total_cost, $sales); */
            }
        // if($update){
        }
?>
<!-- display items with same invoice number -->

<?php
    include "other_sales_details.php";
            // }            
        // }
    // }
?>