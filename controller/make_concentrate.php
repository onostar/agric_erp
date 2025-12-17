<?php
    session_start();
    date_default_timezone_set('Africa/Lagos');
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        $user_id = $_SESSION['user_id'];
        $date = date("Y-m-d H:i:s");
        $production_num = htmlspecialchars(stripslashes($_POST['product_num']));
        $transaction_type = "Production";
        $pineapple_qty = htmlspecialchars(stripslashes($_POST['pineapple']));
        $concentrate_qty = htmlspecialchars(stripslashes($_POST['concentrate']));
        $pineapple_crown_qty = htmlspecialchars(stripslashes($_POST['pineapple_crown']));
        $pineapple_peel_qty = htmlspecialchars(stripslashes($_POST['pineapple_peel']));
        $others_qty = htmlspecialchars(stripslashes($_POST['others']));
        $pineapple_cost = htmlspecialchars(stripslashes($_POST['pineapple_cost']));
        $pineapple_peel_value = htmlspecialchars(stripslashes($_POST['pineapple_peel_value']));
        $pineapple_crown_value = htmlspecialchars(stripslashes($_POST['pineapple_crown_value']));
        /* Generate transaction number */
        $todays_date = date("dmyhis");
        $ran_num = mt_rand(100, 999);
        $trx_num = "TR".$ran_num.$todays_date;
        //insert into production table
        $insert_production = new inserts();
        $values = array( 
            'store'=> $store,
            'posted_by'=> $user_id,
            'pineapple'=> $pineapple_qty,
            'concentrate'=> $concentrate_qty,
            'pineapple_crown'=> $pineapple_crown_qty,
            'pineapple_peel'=> $pineapple_peel_qty,
            'others'=> $others_qty,
            'pineapple_cost'=> $pineapple_cost,
            'pineapple_peel_value'=> $pineapple_peel_value,
            'trx_number' => $trx_num,
            'pineapple_crown_value'=> $pineapple_crown_value,
            'production_num'=> $production_num,
            'date_produced'=> $date
        );
        //check if production for the day already exists
        $check = new selects();
        $check_production = $check->fetch_count_cond('concentrate_production', 'production_num', $production_num);
        if($check_production > 0){
            echo "<div class='success'><p style='background:red'>Production record already exists! <i class='fas fa-thumbs-down'></i></p></div>";
            exit;
        }else{
            $total_pineapple_cost  = $pineapple_qty * $pineapple_cost;
            $total_crown_value = $pineapple_crown_qty * $pineapple_crown_value;
            $total_peel_value = $pineapple_peel_qty * $pineapple_peel_value;
            //calculate cost of production
            $production_cost = $total_pineapple_cost - ($total_crown_value + $total_peel_value);
            $unit_cost = $production_cost / $concentrate_qty;
            //raw materials (pineapple)
            //get pineapple id
            $pines = $check->fetch_details_cond('items', 'item_name', 'PINEAPPLE');
            foreach($pines as $pine){
                $pine_id = $pine->item_id;
            }
            //get pineapple  previous qty
            $pine_qtys = $check->fetch_details_2cond('inventory', 'item', 'store', $pine_id, $store);
            foreach($pine_qtys as $pine_qty){
                $prev_qty = $pine_qty->quantity;
            }
            //pineapple audit
            $pine_audit = array(
                'item'=> $pine_id,
                'transaction'=> $transaction_type,
                'store'=> $store,
                'previous_qty'=> $prev_qty,
                'quantity'=> $pineapple_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $insert_audit = new add_data('audit_trail', $pine_audit);
            $insert_audit->create_data();
            //update pineapple qty in store items
            $new_pine_qty = $prev_qty - $pineapple_qty;
            $updates = new Update_table();
            $updates->update2cond('inventory', 'quantity', 'store', 'item', $new_pine_qty, $store, $pine_id);

            //output products (concentrate first)
            //get concentrate id
            $concs = $check->fetch_details_cond('items', 'item_name', 'CONCENTRATE');
            foreach($concs as $conc){
                $conc_id = $conc->item_id;
                $conc_type = $conc->item_type;
                $conc_reorder = $conc->reorder_level;
            }
            //get concentrate previous qty
            $conc_qtys = $check->fetch_details_cond('inventory', 'item', 'store', $conc_id, $store);
            if(is_array($conc_qtys)){
                foreach($conc_qtys as $conc_qty){
                    $conc_prev_qty = $conc_qty->quantity;
                }
                $new_conc_qty = $conc_prev_qty + $concentrate_qty;
                $updates = new Update_table();
                $updates->update_double2cond('inventory', 'quantity', $new_conc_qty, 'cost_price', $unit_cost, 'store', $store, 'item', $conc_id);
            }else{
                $conc_prev_qty = 0;
                //insert into inventory
                $conc_inventory = array(
                    'item'=> $conc_id,
                    'item_type'=> $conc_type,
                    'store'=> $store,
                    'quantity'=> $concentrate_qty,
                    'reorder_level' => $conc_reorder,
                    'cost_price'=> $unit_cost,
                    'post_date'=> $date
                );
                $add_conc_inventory = new add_data('inventory', $conc_inventory);
                $add_conc_inventory->create_data();
            }
            $conc_audit = array(
                'item'=> $conc_id,
                'transaction'=> 'Production',
                'store'=> $store,
                'previous_qty'=> $conc_prev_qty,
                'quantity'=> $concentrate_qty,
               
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $add_conc_audit = new add_data('audit_trail', $conc_audit);
            $add_conc_audit->create_data();
            
            //update concentrate cost price
            $updates->update('items', 'cost_price', 'item_id', $unit_cost, $conc_id);
            //output product (pineapple peel)
            //get pineapple peel id
            $peels = $check->fetch_details_cond('items', 'item_name', 'PINEAPPLE PEEL');
            foreach($peels as $peel){
                $peel_id = $peel->item_id;
                $peel_type = $peel->item_type;
                $peel_reorder = $peel->reorder_level;
            }

            //get pineapple peel previous qty
            $peel_qtys = $check->fetch_details_2cond('inventory', 'item', 'store', $peel_id, $store);
            if(is_array($peel_qtys)){
                foreach($peel_qtys as $peel_qty){
                    $peel_prev_qty = $peel_qty->quantity;
                }
                $new_peel_qty = $peel_prev_qty + $pineapple_peel_qty;
                $updates->update_double2cond('inventory', 'quantity', $new_peel_qty, 'cost_price', $pineapple_peel_value, 'store', $store, 'item', $peel_id);
            }else{
                $peel_prev_qty = 0;
                //insert into inventory
                $peel_inventory = array(
                    'item'=> $peel_id,
                    'item_type'=> $peel_type,
                    'store'=> $store,
                    'quantity'=> $pineapple_peel_qty,
                    'reorder_level' => $peel_reorder,
                    'cost_price'=> $pineapple_peel_value,
                    'post_date'=> $date
                );
                $add_peel_inventory = new add_data('inventory', $peel_inventory);
                $add_peel_inventory->create_data();
            }
            $peel_audit = array(
                'item'=> $peel_id,
                'transaction'=> 'Production',
                'store'=> $store,
                'previous_qty'=> $peel_prev_qty,
                'quantity'=> $pineapple_peel_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $add_peel_audit = new add_data('audit_trail', $peel_audit);
            $add_peel_audit->create_data();
            //update pineapple peel cost price
            // $updates->update('items', 'cost_price', 'item_id', $pineapple_peel_value, $peel_id);
            //output product (pineapple crown)
            $crowns = $check->fetch_details_cond('items', 'item_name', 'PINEAPPLE CROWN');
            foreach($crowns as $crow){
                $crown_id = $crow->item_id;
                $crown_type = $crow->item_type;
                $crown_reorder = $crow->reorder_level;
            }

            //get pineapple crown previous qty
            $crown_qtys = $check->fetch_details_2cond('inventory', 'item', 'store', $crown_id, $store);
            if(is_array($crown_qtys)){
                foreach($crown_qtys as $crown_qty){
                    $crown_prev_qty = $crown_qty->quantity;
                }
                $new_crown_qty = $crown_prev_qty + $pineapple_crown_qty;
                $updates->update_double2cond('inventory', 'quantity', $new_crown_qty, 'cost_price', $pineapple_crown_value, 'store', $store, 'item', $crown_id);
            }else{
                $crown_prev_qty = 0;
                //insert into inventory
                $crown_inventory = array(
                    'item'=> $crown_id,
                    'item_type'=> $crown_type,
                    'store'=> $store,
                    'quantity'=> $pineapple_crown_qty,
                    'reorder_level' => $crown_reorder,
                    'cost_price'=> $pineapple_crown_value,
                    'post_date'=> $date
                );
                $add_crown_inventory = new add_data('inventory', $crown_inventory);
                $add_crown_inventory->create_data();
            }
            $crown_audit = array(
                'item'=> $crown_id,
                'transaction'=> 'Production',
                'store'=> $store,
                'previous_qty'=> $crown_prev_qty,
                'quantity'=> $pineapple_crown_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $add_crown_audit = new add_data('audit_trail', $crown_audit);
            $add_crown_audit->create_data();
            //update pineapple crown cost price
            // $updates->update('items', 'cost_price', 'item_id', $pineapple_crown_value, $crown_id);
            //insert production record
            $insert_production = new add_data('concentrate_production', $values);
            $insert_production->create_data();

            //transaction record
            //first get inventory ledger
            $invs = $check->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
            foreach($invs as $inv){
                $inventory_ledger = $inv->acn;
                $inv_type = $inv->account_group;
                $inv_sub_group = $inv->sub_group;
                $inv_class = $inv->class;

            }
            //next get production input ledger
            $prods = $check->fetch_details_cond('ledgers', 'ledger', 'PRODUCTION INPUTS');
            foreach($prods as $prod){
                $production_ledger = $prod->acn;
                $production_type = $prod->account_group;
                $production_sub_group = $prod->sub_group;
                $production_class = $prod->class;

            }
            //raw materials inventory credited
            $pineapple_credit_data = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'credit' => $total_pineapple_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Concentrate production - raw materials used',
                'store' => $store,
            );
            $add_pineapple_credit = new add_data('transactions', $pineapple_credit_data);
            $add_pineapple_credit->create_data();
            //production input inventory debited
            $pineapple_debit_data = array(
                'account' => $production_ledger,
                'account_type' => $production_type,
                'sub_group' => $production_sub_group,
                'class' => $production_class,
                'debit' => $total_pineapple_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Concentrate production - raw materials used',
                'store' => $store,
            );
            $add_pineapple_debit = new add_data('transactions', $pineapple_debit_data);
            $add_pineapple_debit->create_data();
            //concentrate inventory debited
            $concentrate_debit = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'debit' => $production_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Concentrate production - Concentrate produced',
                'store' => $store,
            );
            $add_concentrate_debit = new add_data('transactions', $concentrate_debit);
            $add_concentrate_debit->create_data();
            //production input inventory credited
            $concentrate_credit = array(
                'account' => $production_ledger,
                'account_type' => $production_type,
                'sub_group' => $production_sub_group,
                'class' => $production_class,
                'credit' => $production_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Concentrate production - concentrated produced',
                'store' => $store,
            );
            $add_concentrate_credit = new add_data('transactions', $concentrate_credit);
            $add_concentrate_credit->create_data();
            //pineapple crown inventory debited
            $crown_debit = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'debit' => $total_crown_value,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Concentrate production - Pineapple crown produced',
                'store' => $store,
            );
            $add_crown_debit = new add_data('transactions', $crown_debit);
            $add_crown_debit->create_data();
            
            //pineapple peel inventory debited
            $peel_debit = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'debit' => $total_peel_value,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Concentrate production - Pineapple peel produced',
                'store' => $store,
            );
            $add_peel_debit = new add_data('transactions', $peel_debit);
            $add_peel_debit->create_data();
            
            echo "<div class='success'><p style='background:green'>Concentrate production record saved successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }else{
        echo "Session expired. Please log in again";
        header("Location: ../index.php");
    }