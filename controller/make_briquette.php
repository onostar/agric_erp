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
        $briquette_qty = htmlspecialchars(stripslashes($_POST['briquette']));
        $leave_qty = htmlspecialchars(stripslashes($_POST['leaves']));
        $pineapple_crown_qty = htmlspecialchars(stripslashes($_POST['pineapple_crown']));
        $pineapple_peel_qty = htmlspecialchars(stripslashes($_POST['pineapple_peel']));
       
        $leave_cost = htmlspecialchars(stripslashes($_POST['leave_cost']));
        $pineapple_peel_cost = htmlspecialchars(stripslashes($_POST['pineapple_peel_cost']));
        $pineapple_crown_cost = htmlspecialchars(stripslashes($_POST['pineapple_crown_cost']));
        /* Generate transaction number */
        $todays_date = date("dmyhis");
        $ran_num = mt_rand(100, 999);
        $trx_num = "TR".$ran_num.$todays_date;
        $total_leave_cost  = $leave_cost * $leave_qty;
        $total_crown_value = $pineapple_crown_qty * $pineapple_crown_cost;
        $total_peel_value = $pineapple_peel_qty * $pineapple_peel_cost;
        //calculate cost of production
        $production_cost = $total_leave_cost + $total_crown_value + $total_peel_value;
        $unit_cost = $production_cost / $briquette_qty;
        //insert into production table
        $insert_production = new inserts();
        $values = array( 
            'store'=> $store,
            'posted_by'=> $user_id,
            'briquette'=> $briquette_qty,
            'leaves'=> $leave_qty,
            'pineapple_crown'=> $pineapple_crown_qty,
            'pineapple_peel'=> $pineapple_peel_qty,
            'leave_cost'=> $leave_cost,
            'pineapple_peel_cost'=> $pineapple_peel_cost,
            'trx_number' => $trx_num,
            'pineapple_crown_cost'=> $pineapple_crown_cost,
            'total_leave_cost' => $total_leave_cost,
            'total_crown_cost' => $total_crown_value,
            'total_peel_cost' => $total_peel_value,
            'production_num'=> $production_num,
            'date_produced'=> $date
        );
        //check if production for the day already exists
        $check = new selects();
        $check_production = $check->fetch_count_cond('briquette_production', 'production_num', $production_num);
        if($check_production > 0){
            echo "<div class='success'><p style='background:red'>Production record already exists! <i class='fas fa-thumbs-down'></i></p></div>";
            exit;
        }else{
           
            //raw materials (leaves)
            //get leaves id
            $lvs = $check->fetch_details_cond('items', 'item_name', 'LEAVES');
            foreach($lvs as $lv){
                $leave_id = $lv->item_id;
            }
            //get leaves previous qty
            $lv_qtys = $check->fetch_details_2cond('inventory', 'item', 'store', $leave_id, $store);
            foreach($lv_qtys as $lv_qty){
                $leave_prev_qty = $lv_qty->quantity;
            }
            //leaves audit
            $leave_audit = array(
                'item'=> $leave_id,
                'transaction'=> $transaction_type,
                'store'=> $store,
                'previous_qty'=> $leave_prev_qty,
                'quantity'=> $leave_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $insert_audit = new add_data('audit_trail', $leave_audit);
            $insert_audit->create_data();
            //update leaves qty in store items
            $new_leave_qty = $leave_prev_qty - $leave_qty;
            $updates = new Update_table();
            $updates->update2cond('inventory', 'quantity', 'store', 'item', $new_leave_qty, $store, $leave_id);

            //raw materials (peel)
            //get peel id
            $pils = $check->fetch_details_cond('items', 'item_name', 'PINEAPPLE PEEL');
            foreach($pils as $pil){
                $peel_id = $pil->item_id;
            }
            //get peel previous qty
            $peel_qtys = $check->fetch_details_2cond('inventory', 'item', 'store', $peel_id, $store);
            foreach($peel_qtys as $peel_qty){
                $peel_prev_qty = $peel_qty->quantity;
            }
            //peel audit
            $peel_audit = array(
                'item'=> $peel_id,
                'transaction'=> $transaction_type,
                'store'=> $store,
                'previous_qty'=> $peel_prev_qty,
                'quantity'=> $pineapple_peel_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $insert_audit = new add_data('audit_trail', $peel_audit);
            $insert_audit->create_data();
            //update peel qty in store items
            $new_peel_qty = $peel_prev_qty - $pineapple_peel_qty;
            $updates = new Update_table();
            $updates->update2cond('inventory', 'quantity', 'store', 'item', $new_peel_qty, $store, $peel_id);

            //raw material (crown)
            //get crown id 
            $crws = $check->fetch_details_cond('items', 'item_name', 'PINEAPPLE CROWN');
            foreach($crws as $crw){
                $crown_id = $crw->item_id;
            }
            //get crown previous qty
            $crw_qtys = $check->fetch_details_2cond('inventory', 'item', 'store', $crown_id, $store);
            foreach($crw_qtys as $crw_qty){
                $crown_prev_qty = $crw_qty->quantity;
            }
            //crown audit
            $crown_audit = array(
                'item'=> $crown_id,
                'transaction'=> $transaction_type,
                'store'=> $store,
                'previous_qty'=> $crown_prev_qty,
                'quantity'=> $pineapple_crown_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $insert_audit = new add_data('audit_trail', $crown_audit);
            $insert_audit->create_data();
            //update crown qty in store items
            $new_crown_qty = $crown_prev_qty - $pineapple_crown_qty;
            $updates = new Update_table();
            $updates->update2cond('inventory', 'quantity', 'store', 'item', $new_crown_qty, $store, $crown_id);

            //output products (briquette)
            //get briquette id
            $briqs = $check->fetch_details_cond('items', 'item_name', 'BRIQUETTE');
            foreach($briqs as $briq){
                $briquette_id = $briq->item_id;
                $briquette_type = $briq->item_type;
                $briquette_reorder = $briq->reorder_level;
            }
            //get briquette previous qty
            $briq_qtys = $check->fetch_details_cond('inventory', 'item', 'store', $briquette_id, $store);
            if(is_array($briq_qtys)){
                foreach($briq_qtys as $briq_qty){
                    $briquette_prev_qty = $briq_qty->quantity;
                }
                $new_briquette_qty = $briquette_prev_qty + $briquette_qty;
                $updates = new Update_table();
                $updates->update_double2cond('inventory', 'quantity', $new_briquette_qty, 'cost_price', $unit_cost, 'store', $store, 'item', $briquette_id);
            }else{
                $briquette_prev_qty = 0;
                //insert into inventory
                $briq_inventory = array(
                    'item'=> $briquette_id,
                    'item_type'=> $briquette_type,
                    'store'=> $store,
                    'quantity'=> $briquette_qty,
                    'reorder_level' => $briquette_reorder,
                    'cost_price'=> $unit_cost,
                    'post_date'=> $date
                );
                $add_briq_inventory = new add_data('inventory', $briq_inventory);
                $add_briq_inventory->create_data();
            }
            $briq_audit = array(
                'item'=> $briquette_id,
                'transaction'=> 'Production',
                'store'=> $store,
                'previous_qty'=> $briquette_prev_qty,
                'quantity'=> $briquette_qty,
                'posted_by'=> $user_id,
                'post_date'=> $date
            );
            $add_briq_audit = new add_data('audit_trail', $briq_audit);
            $add_briq_audit->create_data();
            //check if briquette is in price list already
            $briq_prices = $check->fetch_count_2cond('prices', 'item', $briquette_id, 'store', $store);
            if($briq_prices > 0){
                  //update briquette cost price
                $updates->update_tripple2cond('prices', 'cost', $unit_cost,'updated_by', $user_id, 'updated_at', $date, 'item', $briquette_id, 'store', $store);
            }else{
                $briq_price = array(
                    'item' => $briquette_id,
                    'store' => $store,
                    'cost' => $unit_cost,
                    'added_by' => $user_id,
                    'added_at' => $date
                );
                $add_briq_price = new add_data('prices', $briq_price);
                $add_briq_price->create_data();
            }
          

            //insert production record
            $insert_production = new add_data('briquette_production', $values);
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
            $raw_credit_data = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'credit' => $production_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Briquette production - raw materials used',
                'store' => $store,
            );
            $add_raw_credit = new add_data('transactions', $raw_credit_data);
            $add_raw_credit->create_data();
            
            //production input debited
            $production_debit_data = array(
                'account' => $production_ledger,
                'account_type' => $production_type,
                'sub_group' => $production_sub_group,
                'class' => $production_class,
                'debit' => $production_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Briquette production - raw materials used',
                'store' => $store,
            );
            $add_production_debit = new add_data('transactions', $production_debit_data);
            $add_production_debit->create_data();
            //briquette inventory debited
            $briquette_debit = array(
                'account' => $inventory_ledger,
                'account_type' => $inv_type,
                'sub_group' => $inv_sub_group,
                'class' => $inv_class,
                'debit' => $production_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'Briquette production - Brique produced',
                'store' => $store,
            );
            $add_briquette_debit = new add_data('transactions', $briquette_debit);
            $add_briquette_debit->create_data();
            //production input inventory credited
            $production_credit = array(
                'account' => $production_ledger,
                'account_type' => $production_type,
                'sub_group' => $production_sub_group,
                'class' => $production_class,
                'credit' => $production_cost,
                'post_date' => $date,
                'posted_by' => $user_id,
                'trx_number' => $trx_num,
                'trans_date' => $date,
                'details' => 'briquette production - concentrated produced',
                'store' => $store,
            );
            $add_prod_credit = new add_data('transactions', $production_credit);
            $add_prod_credit->create_data();

            echo "<div class='success'><p style='background:green'>Briquette production record saved successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    }else{
        echo "Session expired. Please log in again";
        header("Location: ../index.php");
    }