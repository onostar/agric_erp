<div id="tax_rules" class="displays">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        if(isset($_GET['tax'])){
            $tax = $_GET['tax'];
            $get_details = new selects();
            $details = $get_details->fetch_details_cond('tax_rules', 'tax_id', $tax);
            foreach($details as $detail){
                $title = $detail->title;
                $min_income = $detail->min_income;
                $max_income = $detail->max_income;
                $rate = $detail->tax_rate;
               
            }
?>

        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('tax_rules.php')" title="Return to tax rules">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:30%; margin:20px"></div>
    <div class="add_user_form" style="width:40%; margin:20px">
        <h3 style="background:var(--moreColor)">Update <?php echo $title?> Details</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="tax" id="tax" value="<?php echo $tax?>">
                
                <div class="data" style="width:100%">
                    <label for="title">Title</label>
                    <input type="text" name="title" id="title" value="<?php echo $title?>">
                </div>
                <div class="data" style="width:100%">
                    <label for="min_income">Minimum Income (NGN)</label>
                    <input type="number" name="min_income" id="min_income" value="<?php echo $min_income?>">
                </div>
                <div class="data" style="width:100%">
                    <label for="min_income">Maximum Income (NGN)</label>
                    <input type="number" name="max_income" id="max_income" value="<?php echo $max_income?>">
                </div>
                <div class="data" style="width:100%">
                    <label for="rate">Tax Rate (%)</label>
                    <input type="number" name="tax_rate" id="tax_rate" value="<?php echo $rate?>">
                </div>
                    
                <div class="data" style="width:auto">
                    <button type="button" id="add_item" name="add_item" onclick="editTaxRule()">Update record <i class="fas fa-save"></i></button>
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
