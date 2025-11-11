<?php
    session_start();
    date_default_timezone_set("Africa/Lagos");
    $date = date("Y-m-d H:i:s");
    $user = $_SESSION['user_id'];
    $cycle = htmlspecialchars(stripslashes($_POST['cycle']));
    $description = ucwords(htmlspecialchars(stripslashes($_POST['observation'])));
    
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/inserts.php";
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
        "description" => $description,
        "done_by" => $user,
        "post_date" => $date
    );
    $add_task = new add_data("observations", $data);
    $add_task->create_data();
    if($add_task){
        ?>
<div class="notify"><p>Observation added Successfully</p></div>
<h3 style="background:var(--primaryColor)">Remarks/Observations</h3>
    <?php
        //tasks done
        $tsks = $get_details->fetch_details_cond('observations', 'cycle', $cycle);
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

            ?>
            <p>Posted By: <span style="color:brown; text-transform:uppercase"><?php echo $done_by?></span></p>
            <p>Date: <span style="color:brown; text-transform:uppercase"><?php echo date("d M, Y, H:ia", strtotime($tsk->post_date))?></span></p>
        </div>
        <form>
            <div class="inputs">
                
                <div class="data" style="width:100%!important">
                    <label for="notes">Description</label>
                    <textarea name="note" id="note" readonly><?php echo $tsk->description?></textarea>
                </div>
                
            </div>
        </form>
    </div>
    
<?php
    }
}
    }