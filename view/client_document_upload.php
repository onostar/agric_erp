<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;

?>
<div id="direct_sales">
<div id="sales_form" class="displays all_details">
    <div class="add_btn">
        <button onclick="showPage('add_customer.php')">Add New Customer <i class="fas fa-user-plus"></i></button>
        <div class="clear"></div>
    </div>
    <div class="add_user_form" style="width:50%; margin:10px 0;">
        <h3 style="background:var(--labColor); color:#fff; text-align:left!important;">Customer Document Upload</h3>
        
            <!-- search forms -->
        <!-- <form method="POST" id="addUserForm"> -->
            <section class="addUserForm">
                <div class="inputs">
                    <!-- bar items form -->
                    <div class="data" style="width:90%; position:relative">
                    <label for="customer">Select customer</label>
                        <input type="search" name="customer" id="customer" oninput="getCustomersName(this.value, 'get_customer_document.php')" placeholder="Enter customer name or phone number">
                        <div class="search_results" id="search_results">

                        </div>
                    </div>
                    
                </div>
            </section>
            
        </div>
    </div>

</div>
<?php
    }else{
        header("Location: ../index.php");
    }
?>
</div>