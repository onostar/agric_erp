<?php
date_default_timezone_set("Africa/Lagos");
    session_start();  
    $trans_type = "remove";  
    $removed_by = $_SESSION['user_id'];
    $user = $removed_by;
    $store = $_SESSION['store_id'];
    // if(isset($_POST['change_prize'])){
        $item = htmlspecialchars(stripslashes($_POST['item_id']));
        $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
        $reason = ucwords(htmlspecialchars(stripslashes($_POST['remove_reason'])));
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

    
        //get item details
        $get_name = new selects();
        $rows = $get_name->fetch_details_cond('items', 'item_id', $item);
        foreach($rows as $row){
            $item_name = $row->item_name;
            
        }
        //get previous quantity from inventory
        $get_qty = new selects();
        $details = $get_qty->fetch_details_2cond('inventory', 'item', 'store', $item, $store);
        foreach($details as $detail){
            $prev_qty = $detail->quantity;
            $cost = $detail->cost_price;
        }
        if($quantity > $prev_qty){
            echo "<script>alert('Error! You cannot remove more than available quantity');
            </script>";
        }else{
            //data to insert into remove item table
            $data = array(
                'item' => $item,
                'quantity' => $quantity,
                'reason' => $reason,
                'removed_by' => $removed_by,
                'previous_qty' => $prev_qty,
                'cost' => $cost,
                'total_amount' => $cost * $quantity,
                'store' => $store,
                'removed_date' => $date,
                'trx_number' => $trx_num
            );
            //data to insert into audit trail
            $data2 = array(
                'item' => $item,
                'transaction' => $trans_type,
                'quantity' => $quantity,
                'posted_by' => $removed_by,
                'previous_qty' => $prev_qty,
                'store' => $store,
                'post_date' => $date
            );
            //insert into audit trail
            $add_data2 = new add_data('audit_trail', $data2);
            $add_data2->create_data();
            //update quantity in inventory
            $new_qty = $prev_qty - $quantity;
            $change_qty = new Update_table();
            $change_qty->update2cond('inventory', 'quantity', 'item', 'store', $new_qty, $item, $store);
            if($change_qty){
                $add_data = new add_data('remove_items', $data);
                $add_data->create_data();
                if($add_data){
                    //insert into transaction table
                    $total_cost = $cost * $quantity;
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
                    //insert into transaction table
                $debit_data = array(
                    'account' => $cos_ledger,
                    'account_type' => $cos_type,
                    'sub_group' => $cos_group,
                    'class' => $cos_class,
                    'debit' => $total_cost,
                    'details' => 'Loss due to item removal',
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
                    'details' => 'Loss due to item removal',
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
                    echo "<div class='success'><p>$quantity $item_name removed from inventory successfully! <i class='fas fa-thumbs-up'></i></p></div>";
                }
            }else{
                echo "<p style='background:red; color:#fff; padding:5px'>Failed to remove quantity <i class='fas fa-thumbs-down'></i></p>";
            }
        }