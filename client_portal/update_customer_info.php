<div id="update_customer">
<?php
    session_start();
    // $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
        // echo $user_id;
        // if(isset($_GET['customer'])){
            $customer = $_SESSION['user_id'];
            //get customer name
            $get_customer = new selects();
            $rows = $get_customer->fetch_details_cond('customers', 'customer_id', $customer);
            foreach($rows as $row){

?>
    <div class="add_user_form" style="width:70%; margin:10px 50px">
        <h3>Update My Info</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.5rem; justify-content:left">
                <div class="data" style="width:31%">
                    <label for="customer">Name</label>
                    <input type="text" name="customer" id="customer" placeholder="Enter customer name" value="<?php echo $row->customer?>" required>
                    <input type="hidden" name="customer_id" id="customer_id" value="<?php echo $row->customer_id?>" required>
                </div>
                <div class="data" style="width:31%">
                    <label for="phone_number">Phone number</label>
                    <input type="text" name="phone_number" id="phone_number" placeholder="0033421100" required value="<?php echo $row->phone_numbers?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="Address">Address</label>
                    <input type="text" name="address" id="address" required value="<?php echo $row->customer_address?>">
                </div>
                <div class="data" style="width:31%">
                    <label for="email">Email address</label>
                    <input type="text" name="email" id="email" placeholder="example@mail.com" required value="<?php echo $row->customer_email?>">
                </div>
                <input type="hidden" name="customer_type" id="customer_type" value="<?php echo $row->customer_type?>">
                <!-- <div class="data" style="width:31%">
                    <label for="email">Customer Type</label>
                    <select name="customer_type" id="customer_type">
                        <option value="<?php echo $row->customer_type?>" selected><?php echo $row->customer_type?></option>
                        <option value="Landowner">LANDOWNER</option>
                        <option value="Non-Landowner">NON-LANDOWNER</option>
                    </select>
                </div> -->
                <div class="data" style="width:auto">
                    <button type="button" id="update_customer" name="update_customer" onclick="updateCustomer()">Update details <i class="fas fa-save"></i></button>
                </div>
            </div>
           
        </section>    
    </div>

<?php
            
        }
    }else{
        header("Location: ../index.php");
    }
?>
</div>
