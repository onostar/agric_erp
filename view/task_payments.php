<div id="post_purchase">
<?php
    session_start();
    $store = $_SESSION['store_id'];
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";
    if(isset($_SESSION['user_id'])){
        $user_id = $_SESSION['user_id'];
    
    if(isset($_GET['task'])){
        $task = $_GET['task'];
        //get task details
        $get_details= new selects();
        $vens = $get_details->fetch_details_cond('tasks', 'task_id', $task);
        foreach($vens as $ven){
            $title = $ven->title;
            $field = $ven->field;
            $task_no = $ven->task_number;
            $cost = $ven->labour_cost;
        }
        //get invoice details

?>


<div class="displays all_details" style="width:100%!important">
    <!-- <div class="info"></div> -->
    <button class="page_navs" id="back" style="margin:0 50px"onclick="showPage('post_labour.php')"><i class="fas fa-angle-double-left"></i> Back</button>
    <div class="guest_name">
        <div class="displays allResults" id="payment_det">
            <h3 style="background:var(--otherColor); color:#fff; padding:10px;">POST PAYMENT FOR "<?php echo $title?>"</h3>
            <p>Task No #: <?php echo $task_no?></p>
            <div class="payment_detsss">
                
                <div class="pay_form" style="width:70%;">
                    
                    <div class="close_stockin add_user_form" style="width:100%; margin:0;">
                        <section class="addUserForm">
                        <div class="inputs" style="display:flex; flex-wrap:wrap; gap:1rem">
                            
                            <input type="hidden" name="total_amount" id="total_amount" value="<?php echo $cost?>">
                            <input type="hidden" name="task" id="task" value="<?php echo $task?>">
                            <input type="hidden" name="store" id="store" value="<?php echo $store?>">
                            <div class="data" style="width:48%">
                                <label for="">Total Amount</label>
                                <input style="font-size:1rem" type="text" name="invoice_amount" id="invoice_amount" value="<?php echo "₦".number_format($cost)?>" readonly>
                            </div>
                            <div class="data" style="width:48%;">
                                <label for="exp_head"><span class="ledger">Dr. </span>Expense Ledger</label>
                                <select name="exp_head" id="exp_head">
                                    <option value="" selected>Select expense ledger</option>
                                    <?php
                                        
                                        $heads = $get_details->fetch_details_cond('ledgers', 'sub_group', 6);
                                        foreach($heads as $head){
                                    ?>
                                    <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="data" style="width:48%;">
                                <label for="contra"><span class="ledger">Cr. </span>Ledger</label>
                                <select name="contra" id="contra">
                                    <option value="" selected>Select Credit ledger</option>
                                    <?php
                                        $get_heads = new selects();
                                        $heads = $get_heads->fetch_details_eitherCon('ledgers', 'class', 1, 2);
                                        foreach($heads as $head){
                                    ?>
                                    <option value="<?php echo $head->ledger_id?>"><?php echo $head->ledger?></option>
                                    <?php }?>
                                </select>
                            </div>
                            <div class="data">
                                <button onclick="postLabour()" style="background:green; padding:8px; border-radius:5px;font-size:.9rem;">Post Payment <i class="fas fa-hand-holding-dollar"></i></button>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </div>
    
    
</div>
<?php
            }
        
    }else{
        header("Location: ../index.php");
    }
?>
</div>