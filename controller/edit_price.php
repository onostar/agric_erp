<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();    
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $cost_price = htmlspecialchars(stripslashes($_POST['cost_price']));
        $sales_price = htmlspecialchars(stripslashes($_POST['sales_price']));
        $other_price = htmlspecialchars(stripslashes($_POST['other_price']));
       $date = date("Y-m-d H:i:s");
       $user = $_SESSION['user_id'];
       $store = $_SESSION['store_id'];

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";
        include "../classes/inserts.php";

        //check if item price exists
        $check = new selects();
        $count = $check->fetch_count_2cond('prices', 'item', $item, 'store', $store);
        if($count > 0){
            //update price
            $update_price = new Update_table();
            $update_price->update_multiple2condition('prices', 'cost', $cost_price, 'sales_price', $sales_price, 'other_price', $other_price, 'updated_at', $date, 'updated_by', $user, 'item', $item, 'store', $store);
            
        }else{
            //insert price
            $price_data = array(
                'item' => $item,
                'store' => $store,
                'cost' => $cost_price,
                'sales_price' => $sales_price,
                'other_price' => $other_price,
                'added_at' => $date,
                'added_by' => $user
            );
            $insert_price = new add_data('prices', $price_data);
            $insert_price->create_data();
        }
        //check if item exists in inventory to update cost price
        $invs = $check->fetch_count_2cond('inventory', 'item', $item, 'store', $store);
        if($invs > 0){
            //update cost price in inventory
            $update_cost = new update_table();
            $update_cost->update('inventory', 'cost_price', 'item', $cost_price, $item);
        }
        // if($insert_price || $update_price){
             echo "<div class='success'><p>Price updated successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        /* }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        } */
    // }