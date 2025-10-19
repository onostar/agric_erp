<?php
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/inserts.php";
date_default_timezone_set("Africa/Lagos");

    session_start();
    $store = $_SESSION['store_id'];
    $sales_type = "Wholesale";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        if(isset($_GET['invoice'])){
            $invoice = $_GET['invoice'];
        }
        if(isset($_GET['sales_item'])){
            $item = $_GET['sales_item'];
            $customer = $_GET['customer'];
            
        }
    // $_SESSION['customer'] = $customer;
    $quantity = 1;
    $date = date("Y-m-d H:i:s");
    //first check if item is in sales order
    $get_sales = new selects();
    $sales = $get_sales->fetch_count_2cond('sales', 'item', $item, 'invoice', $invoice);
    if($sales > 0){
        echo "<script>
        alert('Item is already on sales order');
        </script>";
        include "wholesale_details.php";
    }else{
    //get selling price
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            $price = $row->sales_price;
            $name = $row->item_name;
            $cost = $row->cost_price;
            // $markup = $row->markup;
            // $department = $row->department;
        }
        //get quantity from inventory
        $get_qty = new selects();
        $qtyss = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
        if(gettype($qtyss) == 'array'){
            $get_qtys = new selects();
            $sums = $get_qtys->fetch_sum_double('inventory', 'quantity', 'store', $store, 'item', $row->item_id);
            foreach($sums as $sum){
                $qty = $sum->total;
            }
        }
        
        if(gettype($qtyss) == 'string'){
            $qty = 0;
        }
        $sales_cost = $quantity * $cost;
            if($qty == 0){
                echo "<script>
                    alert('$name has zero quantity! Cannot proceed');
                    </script>";
                    include "../controller/wholesale_details.php";
            }else if($price == 0){
                echo "<div class='notify'><p><span>$name</span> does not have selling price! Cannot proceed</p></div>";
                include "../controller/wholesale_details.php";
            }else{
                //insert into sales order
                $data = array(
                    'item' => $item,
                    'invoice' => $invoice,
                    'quantity' => $quantity,
                    'price' => $price,
                    'total_amount' => $price,
                    'posted_by' => $user_id,
                    'cost' => $sales_cost,
                    'store' => $store,
                    'sales_type' => $sales_type,
                    'customer' => $customer,
                    'post_date' => $date,
                    // 'markup' => $markup
                );
                $sell_item = new add_data('sales', $data);
                $sell_item->create_data();
                /* $sell_item = new post_sales($item, $invoice, $quantity, $price, $price, $user_id, $sales_cost, $store, $sales_type, $customer, $date, $markup);
                $sell_item->add_sales(); */
                if($sell_item){

        ?>
<!-- display sales for this invoice number -->
<div class="notify"><p><span><?php echo $name?></span> added to sales order</p></div>
<?php
    include "wholesale_details.php";
                }
            }
?>
   
    
<?php
     }
         }
    }else{
        header("Location: ../index.php");
    } 
?>