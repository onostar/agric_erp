<?php
    session_start();    
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $cost_price = htmlspecialchars(stripslashes($_POST['cost_price']));
        $sales_price = htmlspecialchars(stripslashes($_POST['sales_price']));
        // $pack_price = htmlspecialchars(stripslashes($_POST['pack_price']));
        // $wholesale = htmlspecialchars(stripslashes($_POST['wholesale_price']));
       /*  $wholesale_pack = htmlspecialchars(stripslashes($_POST['wholesale_pack']));
        $pack_size = htmlspecialchars(stripslashes($_POST['pack_size'])); */

        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";

        $change_price = new Update_table();
        $change_price->update_double('items', 'sales_price', $sales_price, 'cost_price', $cost_price, 'item_id', $item);
        //update cost price
        $update_price = new update_table();
        $update_price->update('inventory', 'cost_price', 'item', $cost_price, $item);
        if($change_price){
             echo "<div class='success'><p>Price changed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }else{
            echo "<p style='background:red; color:#fff; padding:5px'>Filed to change price <i class='fas fa-thumbs-down'></i></p>";
        }
    // }