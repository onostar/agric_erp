<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    if(isset($_GET['cycle']) && isset($_GET['task_id'])){
        $date = date("Y-m-d H:i:s");
        $user = $_SESSION['user_id'];
        $cycle = htmlspecialchars(stripslashes($_GET['cycle']));
        $task = htmlspecialchars(stripslashes($_GET['task_id']));
        
        //get current date
        $todays_date = date("dmyhis");
        $ran_num ="";
        for($i = 0; $i < 3; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        //generate transaction number
        $invoice = "IN".$ran_num.$cycle.$todays_date;
        include "../classes/dbh.php";
        include "../classes/select.php";
    ?>
        <div class="add_user_form" style="width:50%; margin:10px">
            <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor)">Add Item used for task</h3>
            <!-- <form method="POST" id="addUserForm"> -->
            <section>
                <div class="inputs" style="display:flex!important; gap:.3rem; flex-wrap:wrap; justify-content:left; align-items:flex-end">
                    <input type="hidden" name="task_id" id="task_id" value="<?php echo $task?>">
                    <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>">
                    <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                    <div class="data" style="width:100%!important; margin:5px 0">
                        <label for="item" style="background:none; color:#000; padding:0;margin:0; text-align:left">Item Used</label>
                        <input type="text" name="item" id="item" style="padding:8px;" required placeholder="Search item" onkeyup="getItemDetails(this.value, 'get_task_items.php')" autofocus>
                        <div id="sales_item">
                            
                        </div>
                        <input type="hidden" name="task_item" id="task_item">
                    </div>
                    <div class="data" style="width:30%!important; margin:5px 0">
                        <label for="quantity" style="background:none; color:#000; padding:0; margin:0; text-align:left">Quantity (kg)</label>
                        <input type="number" id="quantity" id="quantity" style="padding:8px;">
                    </div>
                    <div class="data" style="width:auto!important; margin:5px 0!important">
                        <button type="submit" id="add_cat" name="add_cat" onclick="addTaskItem()">Save <i class="fas fa-layer-group"></i></button>
                        <a style="border-radius:15px; background:#dfdfdf;color:#222;padding:8px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="showPage('cycle_details.php?cycle=<?php echo $cycle?>')"><i class="fas fa-close"></i> Close</a>
                    </div>
                </div>
                
            </section>
        </div>
        <div class="displays allResults" id="new_data" style="width:60%!important; margin: 10px 0!important">
        
        </div>
<?php
    }