<div id="penalties" class="displays">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        if(isset($_GET['penalty'])){
            $penalty_id = $_GET['penalty'];
            $get_details = new selects();
            $details = $get_details->fetch_details_cond('penalty_fees', 'penalty_id', $penalty_id);
            foreach($details as $detail){
                $penalty = $detail->penalty;
                $amount = $detail->amount;
            }
?>

        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('tax_rules.php')" title="Return to penalties">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:30%; margin:20px"></div>
    <div class="add_user_form" style="width:40%; margin:20px">
        <h3 style="background:var(--moreColor)">Update <?php echo $penalty?> Details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="penalty_id" id="penalty_id" value="<?php echo $penalty_id?>">
                
                <div class="data" style="width:100%">
                    <label for="penalty">Penalty</label>
                    <input type="text" name="penalty" id="penalty" value="<?php echo $penalty?>">
                </div>
                <div class="data" style="width:100%">
                    <label for="min_income">Fee (NGN)</label>
                    <input type="number" name="amount" id="amount" value="<?php echo $amount?>">
                </div>
                
                <div class="data" style="width:auto">
                    <button type="button" id="add_item" name="add_item" onclick="editPenalty()">Update record <i class="fas fa-save"></i></button>
                </div>
            </div>
        </section>    
    </div>
<?php
    }else{
        echo "<p style='text-align:center; color:red; font-weight:bold'>No crop cycle selected for edit</p>";
    }
}else{
    echo "<p style='text-align:center; color:red; font-weight:bold'>Please login to continue</p>";
}
?>
</div>
