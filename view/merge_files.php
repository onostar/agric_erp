<div id="merge_files" class="displays">

<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $store = $_SESSION['store_id'];
        $user_id = $_SESSION['user_id'];
?>
    <div class="add_user_form" style="width:70%; margin:10px 0;">
        <h3 style="background:var(--tertiaryColor); color:#fff; text-align:left!important;">Merge duplicate files</h3>

        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs" style="justify-content:flex-start;align-items:flex-start">
                <div class="data" id="bar_items" style="width:49%; margin:0">
                    <label for="item"> Select correct customer name</label>
                    
                    <input type="hidden" name="correct_customer" id="correct_customer">
                    <input type="text" name="item" id="item" required placeholder="Input customer name" onkeyup="getCorCustomer(this.value)">
                    <div id="sales_item">
                        
                    </div>
                </div>
                <div class="data" id="bar_items" style="width:49%; margin:0">
                    <label for="item"> Select wrong customer name</label>
                    <input type="hidden" name="wrong_customer" id="wrong_customer">
                    <input type="text" name="item_raw" id="item_raw" required placeholder="Input wrong name" onkeyup="getWrongCustomer(this.value)">
                    <div id="raw_item">
                        
                    </div>
                </div>
                <div class="data">
                    <button style="border-radius:15px; box-shadow:2px 2px 2px #222; margin-top:10px;"onclick="mergeFiles()">merge files <i class="fas fa-file-archive"></i></button>
                </div>
            </div>

        </section>
    </div>
    <div class="info" style="width:100%; margin:0"></div>
    <div class="stocked_in" style="width:100%"></div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>
</div>
