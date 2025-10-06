<?php
   
    
    if(isset($_GET['cycle'])){
        $cycle = $_GET['cycle'];
        $crop = $_GET['crop'];
    //get crop name
    include "../classes/dbh.php";
    include "../classes/select.php";
    $get_cycs = new selects();
    $names = $get_cycs->fetch_details_group('items', 'item_name', 'item_id', $crop);
    $crop_item = $names->item_name;
?>  <div class="info"></div>
    <div class="add_user_form" style="width:50%; margin:10px">
        <h3 style="padding:8px; background:var(--otherColor); font-size:.8rem;text-align:left;color:#fff;">Add Remark/Observation For <?php echo $crop_item?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <input type="hidden" name="cycle" id="cycle" value="<?php echo $cycle?>">
                
                <div class="data" style="width:100%">
                    <label for="description">Observation / Remark</label>
                    <input type="text" name="description" id="description" placeholder="Input Observations" required>
                </div>
                <div class="data" style="width:auto">
                    <button type="submit" id="add_cat" name="add_cat" onclick="addObservation()">Save <i class="fas fa-layer-group"></i></button>
                    <a style="border-radius:15px; background:brown;color:#fff;padding:8px; box-shadow:1px 1px 1px #222"href="javascript:void(0)" onclick="closeAllForms()"><i class="fas fa-close"></i> Close</a>
                </div>
            </div>
            
        </section>    
    </div>

    <?php }?>