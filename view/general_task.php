<?php
    include "../classes/dbh.php";
    include "../classes/select.php";
    session_start();
    $farm = $_SESSION['store_id'];
?>

<div id="add_store" class="displays">
    <div class="info" style="width:35%; margin:20px"></div>
    <div class="add_user_form" style="width:35%; margin:20px">
        <h3 style="background:var(--moreColor)">Add A New Task</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:100%">
                    <label for="task_title">Task title</label>
                    <input type="text" name="task_title" id="task_title" placeholder="Input task title" required>
                </div>
                <div class="data" style="width:100%">
                    <label for="field">Farm Field</label>
                    <select name="field" id="field">
                        <option value="" selected disabled>Select Field</option>
                        <?php
                            //get field
                            $get_details = new selects();
                            $rows = $get_details->fetch_details_cond('fields', 'farm', $farm);
                            foreach($rows as $row){
                        ?>
                        <option value="<?php echo $row->field_id?>"><?php echo $row->field_name?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" style="width:100%">
                    <label for="description">Description</label>
                    <textarea name="description" id="description" placeholder="Brief description of task done" required></textarea>
                </div>
                <div class="data" style="width:100%; margin:5px 0">
                    <label for="item">Item Used</label>
                    <input type="text" name="item" id="item" required placeholder="Search item" onkeyup="getItemDetails(this.value, 'get_task_items.php')">
                    <div id="sales_item">
                        
                    </div>
                    <input type="hidden" name="task_item" id="task_item">
                </div>
                <div class="data" id="item_qty" style="width:49%; margin:5px 0">
                    <label for="quantity">Quantity</label>
                    <input type="number" id="quantity" id="quantity">
                </div>
            </div>
            <div class="inputs">
                <div class="data">
                    <button type="submit" id="add_store" name="add_store" onclick="addTask()">Add task <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
</div>
