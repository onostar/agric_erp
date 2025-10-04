<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $user = $_SESSION['user_id'];
    $date = date("Y-m-d H:i:s");
    $task = htmlspecialchars(stripslashes($_POST['task_id']));
    $item = htmlspecialchars(stripslashes($_POST['task_item']));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));

    include "../classes/dbh.php";
    include "../classes/inserts.php";
    include "../classes/select.php";
    include "../classes/update.php";

    $get_details = new selects();
    //get task details
    $tsks = $get_details->fetch_details_cond('tasks', 'task_id', $task);
    foreach($tsks as $tsk){
        $farm = $tsk->farm;
        $field = $tsk->field;
        $task_title = $tsk->title;
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
    //check if quantity requested is greater than current quantity;
    if($quantity > $prev_qty){
        echo "<script>alert('Error! Quantity required is greater than available quantity, Cannot proceed');</script>";
        return;
    }else{
        //add to task item table
        $data = array(
            'task_id' => $task,
            'item' => $item,
            'quantity' => $quantity,
            'unit_cost' => $unit_cost,
            'total_cost' => $total_cost,
            'posted_by' => $user,
            'post_date' => $date
        );
        $add_data = new add_data('task_items', $data);
        $add_data->create_data();
        if($add_data){
            //add into accounting data

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
?>
    <h2 style="font-size:.9rem; text-align:left;">Items Used for <?php echo $task_title?></h2>
    <table id="data_table" class="searchTable">
        <thead>
            <tr style="background:var(--moreColor)">
                <td>S/N</td>
                <td>Item</td>
                <td>Quantity</td>
                <td></td>
            </tr>
        </thead>
        <tbody>
            <?php
                $n = 1;
                $details = $get_details->fetch_details_cond('task_items', 'task_id', $task);
                if(gettype($details) === 'array'){
                foreach($details as $detail):
            ?>
            <tr>
                <td style="text-align:center; color:red;"><?php echo $n?></td>
                <td>
                    <?php 
                        $str = $get_details->fetch_details_group('items', 'item_name', 'item_id', $detail->item);
                        echo $str->item_name;
                    ?>
                </td>
                <td style="color:green; text-align:center">
                    <?php 
                        echo $detail->quantity;
                    ?>
                </td>
                <td><a href="javascript:void(0)" title="delete item" style="color:red;" onclick="removeTaskItem('<?php echo $detail->task_item_id?>')"><i class="fas fa-trash"></i></a></td>
            </tr>
            
            <?php $n++; endforeach;}?>
        </tbody>
    </table>
    
    <?php
        if(gettype($details) == "string"){
            echo "<p class='no_result'>'$details'</p>";
        }
    
    ?>
<?php
    }
    