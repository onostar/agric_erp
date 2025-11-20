<div id="upload_receipt_div">
<?php
    session_start();
    // $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_SESSION['user_id'])){
        $customer = $_SESSION['user_id'];
        
        // echo $user_id;
        if(isset($_GET['assigned_id'])){
            $assigned = $_GET['assigned_id'];
            //get details of assigned field
            $get_details = new selects();
            $rows = $get_details->fetch_details_cond('assigned_fields', 'assigned_id', $assigned);
            foreach($rows as $row){
                $field = $row->field;
            }
            //get field details 
            $field_details = $get_details->fetch_details_cond('fields', 'field_id', $field);
            foreach($field_details as $field_detail){
                $field_name = $field_detail->field_name;
            }

?>
    <a href="javascript:void(0)" onclick="showPage('upload_payment.php')" style="background:brown; color:#fff; padding:5px; border-radius:15px; text-decoration:none; border:1px solid #fff; box-shadow:1px 1px 1px #222; margin:10px 50px"><i class="fas fa-angle-double-left"></i> Return</a>
    <div class="add_user_form" style="width:50%; margin:10px 50px">
        <h3>Upload payment for <?php echo $field_name?></h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section>
            <div class="inputs" style="gap:.5rem; justify-content:left">
                <div class="data" style="width:48%">
                    <input type="hidden" name="assigned_id" id="assigned_id" value="<?php echo $assigned?>" required>
                    <input type="hidden" name="customer" id="customer" value="<?php echo $customer?>" required>
                    <label for="receipt">Upload payment evidence</label>
                    <input type="file" name="receipt" id="receipt" placeholder="Upload payment evidence" required>
                </div>
                <div class="data" style="width:48%">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" placeholder="Enter amount paid" required>
                </div>
                <div class="data" style="width:48%">
                    <label for="remark">Remark</label>
                    <input type="text" name="remark" id="remark" placeholder="Enter remark">
                </div>
               
                <div class="data" style="width:auto">
                    <button type="button" id="update_customer" name="update_customer" onclick="uploadReceipt()">Upload Rceipt <i class="fas fa-save"></i></button>
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
