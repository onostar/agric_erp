<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    if(isset($_GET['task_item_id'])){
        $id = htmlspecialchars(stripslashes($_GET['task_item_id']));

    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    include "../classes/update.php";
    include "../classes/delete.php";

    $get_details = new selects();
    //get task item details
    $tsks = $get_details->fetch_details_cond('task_items', 'task_item_id', $id);
    foreach($tsks as $tsk){
        $item = $tsk->item;
        $quantity = $tsk->quantity;
        $task = $tsk->task_id;
        $trx_number = $tsk->trx_number;
        $invoice = $tsk->invoice;
    }
    //get task details
    $rows = $get_details->fetch_details_cond('tasks', 'task_id', $task);
    foreach($rows as $row){
        $farm = $row->farm;
        $task_title = $row->title;
    }
    //get item details
    $results = $get_details->fetch_details_cond('items', 'item_id', $item);
    foreach($results as $result){
        $item_name = $result->item_name;
        $unit_cost = $result->cost_price;
        $item_type = $result->item_type;
        $reorder_level = $result->reorder_level;
    }
    //delete from task items
    $delete = new deletes();
    $delete->delete_item('task_items', 'task_item_id', $id);
    if($delete){
        //return quantity back to inventory
        //get current quantity
        $prevs = $get_details->fetch_details_cond('inventory', 'item', $item);
        if(is_array($prevs)){
            foreach($prevs as $prev){
                $prev_qty = $prev->quantity;
            }
            //update current quantity in inventory
            $new_qty = $prev_qty + $quantity;
            $update_inventory = new Update_table();
            $update_inventory->update_double2Cond('inventory', 'quantity', $new_qty, 'cost_price', $unit_cost, 'item', $item, 'store', $farm);
        }else{
            $prev_qty = 0;
            //add to inventory if not found
            $inventory_data = array(
                'item' => $item,
                'cost_price' => $unit_cost,
                'quantity' => $quantity,
                'reorder_level' => $reorder_level,
                'store' => $farm,
                'item_type' => $item_type
            );
            $insert_item = new add_data('inventory', $inventory_data);
            $insert_item->create_data();
        }
        
        //data to insert into audit trail
        $audit_data = array(
            'item' => $item,
            'transaction' => 'task return',
            'previous_qty' => $prev_qty,
            'quantity' => $quantity,
            'posted_by' => $user,
            'store' => $farm,
            'post_date' => $date
        );
        //insert into audit trail
        $inser_trail = new add_data('audit_trail', $audit_data);
        $inser_trail->create_data();
   
        //return transaction from accounting data
        $delete_trx = new deletes();
        $delete_trx->delete_item('transactions', 'trx_number', $trx_number);
?>
        <div class="notify"><p><?php echo $item_name?> removed Successfully</p></div>
<?php
        }
    include "../controller/task_items.php";
    }
    