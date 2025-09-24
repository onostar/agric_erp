<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $task = strtoupper(htmlspecialchars(stripslashes($_POST['task_title'])));
    $item = htmlspecialchars(stripslashes($_POST['task_item']));
    $description = ucwords(htmlspecialchars(stripslashes($_POST['description'])));
    $quantity = htmlspecialchars(stripslashes($_POST['quantity']));
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
    include "../classes/update.php";
    //get cycle details
    $get_details = new selects();
    $rows = $get_details->fetch_details_cond('crop_cycles', 'cycle_id', $cycle);
    foreach($rows as $row){
        $field = $row->field;
        $farm = $row->farm;
    }

    $data = array(
        "cycle" => $cycle,
        "farm" => $farm,
        "field" => $field,
        "title" => $task,
        "description" => $description,
        "item" => $item,
        "quantity" => $quantity,
        "done_by" => $user,
        "post_date" => $date
    );
    $add_task = new add_data("tasks", $data);
    $add_task->create_data();
    if($add_task){
        //check if item was used and remove quantity from inventory;
        if($quantity > 0){
            //get previous quantity
            $prevs = $get_details->fetch_details_group('inventory', 'quantity', 'item', $item);
            $prev_qty = $prevs->quantity;
            //update  quantity in inventory
            $update_qty = new Update_table();
            $update_qty->update_inv_qty($quantity, $item, $farm);

            //add to audit trail
            $inser_trail = new audit_trail($item, 'task', $prev_qty, $quantity, $user, $farm, $date);
            $inser_trail->audit_trail();
        }
        ?>
<div class="notify"><p>Task added Successfully</p></div>
<h3 style="background:var(--primaryColor)">Tasks Done</h3>
        <?php
            //tasks done
            $tsks = $get_details->fetch_details_cond('tasks', 'cycle', $cycle);
            if(is_array($tsks)){
                foreach($tsks as $tsk){
            
        ?>
        <div id="tasks_done">
            <div class="consultant" style="display:flex; gap:1rem; flex-wrap:wrap; padding:5px; margin-bottom:10px">
                <?php
                    //get consultant name
                    $cons = $get_details->fetch_details_cond('users', 'user_id', $tsk->done_by);
                    foreach($cons as $con){
                        $done_by = $con->full_name;
                    };
                    //get item name
                    $serv = $get_details->fetch_details_cond('items', 'item_id', $tsk->item);
                    if(is_array($serv)){
                        foreach($serv as $ser){
                            $item_name = $ser->item_name;
                        };
                    }else{
                        $item_name = "None";
                    }
                ?>
                <p>Done By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
                <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($tsk->post_date))?></span></p>
            </div>
            <form>
                <div class="inputs">
                    <div class="data" style="width:50%!important">
                        <label for="notes">Task</label>
                        <textarea  readonly><?php echo $tsk->title?></textarea>
                    </div>
                    
                    <div class="data" style="width:90%!important">
                        <label for="notes">Description</label>
                        <textarea name="note" id="note" readonly><?php echo $tsk->description?></textarea>
                    </div>
                    <div class="data" style="width:40%!important">
                        <label for="notes">Item Used</label>
                        <input type="text" id="note" readonly value="<?php echo $item_name?>">
                    </div>
                    <div class="data">
                        <label for="notes">Quantity Used</label>
                        <input type="number" readonly value="<?php echo $tsk->quantity?>">
                    </div>
                </div>
            </form>
        </div>
        <?php }}?>
<?php
    }