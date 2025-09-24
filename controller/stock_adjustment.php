<?php
    date_default_timezone_set("Africa/Lagos");
    session_start();
    $trans_type = "adjust";  
    $adjusted_by = $_SESSION['user_id'];
    $user = $adjusted_by;
    $store = $_SESSION['store_id'];
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
        $date = date("Y-m-d H:i:s");
        $trx_date = $date;
        $todays_date = date("dmyhis");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $trx_num = "TR".$ran_num.$todays_date;
        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/update.php";
        include "../classes/select.php";
        include "../classes/inserts.php";
        //get item quantity in inventory;
        $get_inv = new selects();
        $inv_qtys = $get_inv->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
        foreach($inv_qtys as $inv_qty){
            $prev_qty = $inv_qty->quantity;
            $cost = $inv_qty->cost_price;
        }
        //data to insert in stock adjustment
        $data = array(
            'item' => $item,
            'adjusted_by' => $adjusted_by,
            'previous_qty' => $prev_qty,
            'new_qty' => $quantity,
            'store' => $store,
            'adjust_date' => $date
        );
        //data to insert in audit trail
        $data2 = array(
            'transaction' => $trans_type,
            'item' => $item,
            'posted_by' => $adjusted_by,
            'previous_qty' => $prev_qty,
            'quantity' => $quantity,
            'store' => $store,
            'post_date' => $date
        );
        //insert into audit trail
        $add_data2 = new add_data('audit_trail', $data2);
        $add_data2->create_data();
        //get item details
        $get_name = new selects();
        $rows = $get_name->fetch_details_cond('items', 'item_id', $item);
        foreach($rows as $row){
            $item_name = $row->item_name;
        }
        //update quantity in inventory
        $change_qty = new Update_table();
        $change_qty->update2cond('inventory', 'quantity', 'item', 'store', $quantity, $item, $store);
        if($change_qty){
            //insert into stock adjustment table
            $add_data = new add_data('stock_adjustments', $data);
            $add_data->create_data();
            if($add_data){
                //insert into transaction table
                //get ledger account numbers and account type
                $get_exp = new selects();
                $exps = $get_exp->fetch_details_cond('ledgers', 'ledger', 'INVENTORY ADJUSTMENT');
                foreach($exps as $exp){
                    $cos_ledger = $exp->acn;
                    $cos_type = $exp->account_group;
                    $cos_group = $exp->sub_group;
                    $cos_class = $exp->class;
                }
                //get contra ledger account number
                $get_contra = new selects();
                $cons = $get_contra->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
                foreach($cons as $con){
                    $inv_ledger = $con->acn;
                    $inv_type = $con->account_group;
                    $inv_group = $con->sub_group;
                    $inv_class = $con->class;
                }
                
                if($quantity < $prev_qty){
                    $removed_qty = $prev_qty - $quantity;
                    $total_cost = $cost * $removed_qty;
                    //insert into transaction table
                    $debit_data = array(
                        'account' => $cos_ledger,
                        'account_type' => $cos_type,
                        'sub_group' => $cos_group,
                        'class' => $cos_class,
                        'debit' => $total_cost,
                        'details' => 'Loss due to item adjustment',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date

                    );
                    $credit_data = array(
                        'account' => $inv_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_group,
                        'class' => $inv_class,
                        'credit' => $total_cost,
                        'details' => 'Loss due to item adjustment',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date

                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data(); 

                    //add into remove item table
                    $remove_data = array(
                        'item' => $item,
                        'quantity' => $removed_qty,
                        'reason' => 'Stock Adjustment',
                        'removed_by' => $user,
                        'previous_qty' => $prev_qty,
                        'cost' => $cost,
                        'total_amount' => $total_cost,
                        'store' => $store,
                        'removed_date' => $date,
                        'trx_number' => $trx_num
                    );
                    $add_remove = new add_data('remove_items', $remove_data);
                    $add_remove->create_data();
                }else{
                    $added_qty = $quantity - $prev_qty;
                    $total_cost = $cost * $added_qty;
                    //insert into transaction table
                    $credit_data = array(
                        'account' => $cos_ledger,
                        'account_type' => $cos_type,
                        'sub_group' => $cos_group,
                        'class' => $cos_class,
                        'credit' => $total_cost,
                        'details' => 'Loss due to item adjustment',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date

                    );
                    $debit_data = array(
                        'account' => $inv_ledger,
                        'account_type' => $inv_type,
                        'sub_group' => $inv_group,
                        'class' => $inv_class,
                        'debit' => $total_cost,
                        'details' => 'Loss due to item adjustment',
                        'post_date' => $date,
                        'posted_by' => $user,
                        'trx_number' => $trx_num,
                        'trans_date' => $trx_date

                    );
                    //add debit
                    $add_debit = new add_data('transactions', $debit_data);
                    $add_debit->create_data();      
                    //add credit
                    $add_credit = new add_data('transactions', $credit_data);
                    $add_credit->create_data();
                }
                
                
                echo "<div class='success'><p>$item_name quantity adjusted successfully! <i class='fas fa-thumbs-up'></i></p></div>";
            }
        }
    // }