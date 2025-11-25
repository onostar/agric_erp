<div id="exchange_rate" class="displays">
<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        if(isset($_GET['rate'])){
            $exchange = $_GET['rate'];
            $get_details = new selects();
            $details = $get_details->fetch_details_cond('exchange_rates', 'exchange_id', $exchange);
            foreach($details as $detail){
                
                $rate = $detail->rate;
               
            }
?>

        <a style="background:brown; color:#fff; padding:5px 8px; border-radius:15px; border:1px solid #fff; box-shadow:1px 1px 1px #222;" href="javascript:void(0)" onclick="showPage('exchange_rate.php')" title="Return to tax rules">Return <i class="fas fa-angle-double-left"></i></a>

    <div class="info" style="width:50%; margin:20px"></div>
    <div class="add_user_form" style="width:40%; margin:20px">
        <h3 style="background:var(--tertiaryColor)">Update Dollar - Naira Exchange Rate</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.8rem; align-items:flex_end; justify-content:left;">
                <input type="hidden" name="exchange" id="exchange" value="<?php echo $exchange?>">
                
                <div class="data" style="width:50%">
                    <label for="rate">Rate</label>
                    <input type="number" name="rate" id="rate" value="<?php echo $rate?>">
                </div>
                
                    
                <div class="data" style="width:auto">
                    <button type="button" id="add_item" name="add_item" onclick="editExchangeRate()">Update record <i class="fas fa-save"></i></button>
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
