<div id="edit_item_price">
<?php

    include "../classes/dbh.php";
    include "../classes/select.php";
    
    if(isset($_SESSION['success'])){
        echo $_SESSION['success'];
    }

?>

    <div class="info" style="width:100%; margin:0!important"></div>
    <div class="displays allResults" style="width:80%;">
        <!-- <h2>Post Other Transactions</h2> -->
        <!-- <hr> -->
        
            <section class="addUserForm" >
                <div class="add_user_form" style="width:85%; margin:10px 0; box-shadow:none;">
                    <h3 style="background:var(--primaryColor); color:#fff; text-align:left!important;" >Post Other Transactions </h3>
                    <div class="inputs" style="padding:20px">
                        <!-- bar items form -->
                        <div class="data" id="bar_items" style="width:100%; margin:20px 0">
                            <!-- <a style="background:#e8e8e8;color:#222; padding:8px; border-radius:15px; box-shadow:1px 1px 1px #222; margin:10px; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('post_inventories.php')" title="Post Loans"><i class="fas fa-toolbox"></i> Inventories/Cost of Sales</a> -->
                            <a style="background:#e8e8e8;color:#222; padding:8px; border-radius:15px; box-shadow:1px 1px 1px #222; margin:10px; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('post_loans.php')" title="Post Loans"><i class="fas fa-hand-holding-dollar"></i> Loans</a>
                            <a style="background:#e8e8e8;color:#222; padding:8px; border-radius:15px; box-shadow:1px 1px 1px #222; margin:10px; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('post_director_trx.php')" title="Director Transactions"><i class="fas fa-user-tie"></i> Director's Transactions</a>
                            <a style="background:#e8e8e8;color:#222; padding:8px; border-radius:15px; box-shadow:1px 1px 1px #222; margin:10px; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('post_finance_cost.php')" title="Other Finance Cost"><i class="fas fa-bank"></i> Loan Fees, Bank charges & Tax</a>
                            <a style="background:#e8e8e8;color:#222; padding:8px; border-radius:15px; box-shadow:1px 1px 1px #222; margin:10px; border:1px solid #fff" href="javascript:void(0)" onclick="showPage('post_others.php')" title="Other Transactions"><i class="fas fa-dolly-box"></i> Other Transactions</a>
                        </div>
                    
                    </div>
                </div>
            </section>
</div>