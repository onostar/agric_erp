<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $task = htmlspecialchars(stripslashes($_POST['task_id']));
    $invoice = htmlspecialchars(stripslashes($_POST['invoice']));
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $item = htmlspecialchars(stripslashes($_POST['task_item']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    $details = "Item used for task";
    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    include "../classes/update.php";
    //get current date
    $todays_date = date("dmyhis");
    $ran_num ="";
    for($i = 0; $i < 3; $i++){
        $random_num = random_int(0, 9);
        $ran_num .= $random_num;
    }
    //generate transaction number
    $trx_num = "TR".$ran_num.$todays_date;
    $get_details = new selects();
    //get task details
    $tsks = $get_details->fetch_details_cond('tasks', 'task_id', $task);
    foreach($tsks as $tsk){
        $farm = $tsk->farm;
        $field = $tsk->field;
        $task_title = $tsk->title;
        // $cycle = $tsk->cycle;
    }
    //get item details
    $results = $get_details->fetch_details_cond('items', 'item_id', $item);
    foreach($results as $result){
        $item_name = $result->item_name;
        $unit_cost = $result->cost_price;
    }
    $total_cost = $quantity * $unit_cost;
    //get current quantity
    $prevs = $get_details->fetch_details_group('inventory', 'quantity', 'item', $item);
    $prev_qty = $prevs->quantity;
    //check if item already exisits for this task
    $checks = $get_details->fetch_count_3cond('task_items', 'task_id', $task, 'item', $item, 'invoice', $invoice);
    if($checks > 0){
        echo "<script>alert('Error! Selected Item already exists for this task');</script>";
        include "../controller/task_items.php";
        return;
    }else{
        //check if quantity requested is greater than current quantity;
        if($quantity > $prev_qty){
            echo "<script>alert('Error! Quantity required is greater than available quantity, Cannot proceed');</script>";
            return;
        }else{
            //add to task item table
            $data = array(
                'task_id' => $task,
                'cycle' => $cycle,
                'farm' => $farm,
                'field' => $field,
                'item' => $item,
                'quantity' => $quantity,
                'unit_cost' => $unit_cost,
                'total_cost' => $total_cost,
                'invoice' => $invoice,
                'trx_number' => $trx_num,
                'posted_by' => $user,
                'post_date' => $date
            );
            $add_data = new add_data('task_items', $data);
            $add_data->create_data();
            if($add_data){
                //check if task is planting and update expected harvest quantity
                if($task_title == "PLANTING" && $item_name == "SUCKER"){
                    //get total suckers planted in this cycle
                    $total_suckers = $get_details->fetch_sum_double('task_items', 'quantity', 'cycle', $cycle, 'item', $item);
                    foreach($total_suckers as $sucker){
                        $suckers_planted = $sucker->total;
                    }
                    //update expected yield quantity in crop cycle table
                    $update_cycle = new Update_table();
                    $update_cycle->update('crop_cycles', 'expected_yield', 'cycle_id', $suckers_planted, $cycle);
                }
                //add into accounting data
                //get farm input ledger
                if($cycle == 0 || $cycle == ""){    
                    $inps = $get_details->fetch_details_cond('ledgers', 'ledger', 'GENERAL FIELD MAINTENANCE');
                }else{
                    $inps = $get_details->fetch_details_cond('ledgers', 'ledger', 'FARM INPUTS');
                }
                foreach($inps as $inp){
                    $contra_ledger = $inp->acn;
                    $contra_type = $inp->account_group;
                    $contra_sub_group = $inp->sub_group;
                    $contra_class = $inp->class;

                }
                //get inventory legder id
                $invs = $get_details->fetch_details_cond('ledgers', 'ledger', 'INVENTORIES');
                foreach($invs as $inv){
                    $inventory_ledger = $inv->acn;
                    $inv_type = $inv->account_group;
                    $inv_sub_group = $inv->sub_group;
                    $inv_class = $inv->class;

                }
                $credit_data = array(
                    'account' => $inventory_ledger,
                    'account_type' => $inv_type,
                    'sub_group' => $inv_sub_group,
                    'class' => $inv_class,
                    'credit' => $total_cost,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'details' => $details,
                    'trans_date' => $date,
                    'store' => $farm
                );
                $debit_data = array(
                    'account' => $contra_ledger,
                    'account_type' => $contra_type,
                    'sub_group' => $contra_sub_group,
                    'class' => $contra_class,
                    'debit' => $total_cost,
                    'post_date' => $date,
                    'posted_by' => $user,
                    'trx_number' => $trx_num,
                    'details' => $details,
                    'trans_date' => $date,
                    'store' => $farm

                );
                //add debit
                $add_debit = new add_data('transactions', $debit_data);
                $add_debit->create_data();      
                //add credit
                $add_credit = new add_data('transactions', $credit_data);
                $add_credit->create_data();
                //update  quantity in inventory
                $update_qty = new Update_table();
                $update_qty->update_inv_qty($quantity, $item, $farm);

                //add to audit trail
                $audit_data = array(
                    'store' => $farm,
                    'item' => $item,
                    'transaction' => 'task',
                    'previous_qty' => $prev_qty,
                    'quantity' => $quantity,
                    'posted_by' => $user,
                    'post_date' => $date
                );
                $add_audit = new add_data('audit_trail', $audit_data);
                $add_audit->create_data();
?>
        <div class="notify"><p><?php echo $item_name?> added Successfully</p></div>
<?php
        
    }
    include "../controller/task_items.php";
?>
    
<?php
        }
    
}
    