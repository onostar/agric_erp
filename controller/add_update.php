<?php
// instantiate class
include "../classes/dbh.php";
include "../classes/select.php";
include "../classes/inserts.php";
include "../classes/update.php";
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
    $_SESSION['customer'] = $customer;
    //get customer type to determine price
    $get_customer = new selects();
    $rows = $get_customer->fetch_details_group('customers', 'customer_type', 'customer_id', $customer);
    $type = $rows->customer_type;

    $date = date("Y-m-d H:i:s");
    $quantity = 1;
    
    //get selling price
    
    $get_item = new selects();
    $rows = $get_item->fetch_details_cond('items', 'item_id', $item);
     if(gettype($rows) == 'array'){
        foreach($rows as $row){
            if($type == "Dealer"){
                $price = $row->wholesale;
            }else{
                $price = $row->sales_price;

            }
            $name = $row->item_name;
            $cost = $row->cost_price;
            // $department = $row->department;
        }
        //get quantity from inventory
        $get_qty = new selects();
        $qtyss = $get_qty->fetch_details_2cond('inventory', 'store', 'item', $store, $item);
        if(gettype($qtyss) == 'array'){
            foreach($qtyss as $qtys){
                $qty = $qtys->quantity;
            }
        }
        if(gettype($qtyss) == 'string'){
            $qty = 0;
        }
        $sales_cost = $quantity * $cost;
            if($qty == 0){
                /* echo "<div class='notify'><p><span>$name</span> has zero quantity! Cannot proceed</p>"; */
                echo "<script>
                    alert('$name has zero quantity! Cannot proceed');
                </script>";
    include "update_details.php";
            }else if($price == 0){
                /* echo "<div class='notify'><p><span>$name</span> does not have selling price! Cannot proceed</p></div>"; */
                echo "<script>
                    alert('$name does not have selling price! Cannot proceed');
                </script>";
    include "update_details.php";
            }else{
                //insert into sales order
                $sell_item = new post_sales($item, $invoice, $quantity, $price, $price, $user_id, $sales_cost, $store, $sales_type, $customer, $date);
                $sell_item->add_sales();
                if($sell_item){
                    //reduce inventory
                    $update_inv = new Update_table();
                    $update_inv->update_inv_qty($quantity, $item, $store);
        ?>
<!-- display sales for this invoice number -->
<div class="notify"><p><span><?php echo $name?></span> added to sales order</p></div>
<?php
    include "update_details.php";
                }
            }
?>
   
    
<?php
         }
    }else{
        header("Location: ../index.php");
    } 
?>