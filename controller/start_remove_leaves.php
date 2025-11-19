<?php
    if(isset($_GET['cycle']) && isset($_GET['task_id'])){
        $cycle = $_GET['cycle'];
        $task_id = $_GET['task_id'];
    //get crop name
    include "../classes/dbh.php";
    include "../classes/select.php";
   /*  $get_cycs = new selects();
    $names = $get_cycs->fetch_details_group('items', 'item_name', 'item_id', $crop);
    $crop_item = $names->item_name; */
?>  <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px">
        <h3 style="padding:8px; font-size:.8rem;text-align:left;background:var(--tertiaryColor); color:#fff;">Remove Leaves</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                <input type="hidden" name="task_id" id="task_id" value="<?php echo $task_id?>">
               
                <div class="data" style="width:49%; margin:5px 0">
                    <label for="quantity">Quantity (kg)</label>
                    <input type="number" id="quantity" id="quantity">
                </div>
                <div class="data" style="width:auto">
                    <button type="button" id="add_cat" name="add_cat" onclick="removeLeaves()">Save <i class="fas fa-layer-group"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="closeAllForms()"><i class="fas fa-close"></i> Close form</a>
                </div>
            </div>
            
        </section>    
    </div>

    <?php }?>