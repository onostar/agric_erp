<?php
   
    
    if(isset($_GET['cycle'])){
        $cycle = $_GET['cycle'];
        // $crop = $_GET['crop'];
    include "../classes/dbh.php";
    include "../classes/select.php";
?>  <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px">
        <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor)">Start Pruning For CY0<?php echo $cycle?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="gap:1rem">
                <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                <div class="data" style="width:48%">
                    <label for="task_title">Task</label>
                    <input type="text" name="task_title" id="task_title" value="PRUNING" readonly>
                </div>
                <div class="data" style="width:48%">
                    <label for="start_date">Start date</label>
                    <input type="datetime-local" name="start_date" id="start_date" value="<?php echo date("Y-m-d H:i")?>" required>
                </div>
                
                <div class="data" style="width:100%">
                    <label for="description">Assigned Workers</label>
                    <textarea name="workers" id="workers" placeholder="Input Names of Workers involved in this task (seperate by a comma)" required></textarea>
                </div>
                
                <div class="data" style="width:auto">
                    <button type="type" id="add_cat" name="add_cat" onclick="addCycleTask()">Save <i class="fas fa-layer-group"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="closeAllForms()"><i class="fas fa-close"></i> Close</a>
                </div>
            </div>
            
        </section>    
    </div>

    <?php }?>