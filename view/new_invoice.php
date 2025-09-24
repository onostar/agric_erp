<div id="sales_order">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        if(isset($_GET['customer'])){
            $customer = $_GET['customer'];
            //get customer name
            $get_customer = new selects();
            $rows = $get_customer->fetch_details_cond('customers', 'customer_id', $customer);
            foreach($rows as $row){
                $customer_name = $row->customer;
            }
?>
<div id="sales_form" class="displays all_details">
    <?php
        //generate receipt invoice
        //get current date
        $todays_date = date("dmyhi");
        $ran_num ="";
        for($i = 0; $i < 5; $i++){
            $random_num = random_int(0, 9);
            $ran_num .= $random_num;
        }
        $invoice = "PR".$ran_num.$todays_date.$user_id;
        // $_SESSION['invoice'] = $invoice;
    ?>
    
    <div class="add_user_form" style="width:50%; margin:10px 0; box-shadow:none">
        <h3 style="background:var(--primaryColor); color:#ff; text-align:left!important;" >New Invoice for  <?php echo $customer_name ." (".$invoice.")"?></h3>
        
            <!-- search forms -->
        <!-- <form method="POST" id="addUserForm"> -->
            <section class="addUserForm">
                <div class="inputs">
                    <!-- bar items form -->
                    <input type="hidden" name="customer" id="customer" value="<?php echo $customer?>">
                    <!-- <div class="data" style="width:100%">
                        <label for="">Name of Item</label>
                        <input type="text" name="drug" id="drug">
                    </div> -->
                    <div class="data" id="bar_items" style="width:100%; margin:2px 0">
                        <label for="item"> Enter details</label>
                        <input type="hidden" name="invoice" id="invoice" value="<?php echo $invoice?>">
                        <input type="hidden" name="posted_by" id="posted_by" value="<?php echo $user_id?>">
                        <textarea name="details" id="details" placeholder="Type patient prescription"></textarea>
                    </div>
                    <div class="data">
                        <label for="amount">Agreed Amount (NGN)</label><input type="text" name="amount" id="amount">
                    </div>
                    <div class="data">
                        <button stype="submit" onclick="addInvoice()">Add <i class="fas fa-capsules"></i></button>
                    </div>
                </div>
            </section>
            
        </div>
    </div>


<!-- for editing item quantitiy and price -->
<div class="show_more" id="showMore"></div>
<!-- showing all items in the sales order -->
<div class="sales_order"></div>
<?php
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>