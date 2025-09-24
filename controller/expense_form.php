<?php
    session_start();
    include "../classes/dbh.php";
    include "../classes/select.php";
    if(isset($_GET['expense_id'])){
        $expense = $_GET['expense_id'];
        //get expense details
        $get_expense = new selects();
        $rows = $get_expense->fetch_details_cond('expenses', 'expense_id', $expense);
        foreach($rows as $row){

?>
<div class="add_user_form priceForm" style="width:90%; margin:5px 0;">
        <h3 style="background:var(--otherColor); text-align:left">Edit Expense</h3>
        <!-- <form method="POST" id="addUserForm"> -->
        <section class="addUserForm">
            <div class="inputs">
                <div class="data" style="width:32%; margin:5px 0;">
                    <input type="hidden" name="exp_id" id="exp_id", value="<?php echo $expense?>">
                    <label for="exp_date">Transaction Date</label>
                    <input type="date" name="exp_date" id="exp_date" value="<?php echo date('Y-m-d', strtotime($row->expense_date))?>">
                </div>
                <div class="data" style="width:32%; margin:5px 0;">
                    <label for="exp_head">Expense Head</label>
                    <select name="exp_head" id="exp_head">
                        <?php
                            $get_exp = new selects();
                            $cur_exp_head = $get_exp->fetch_details_group('expense_heads', 'expense_head', 'exp_head_id', $row->expense_head);
                            $cur_head = $cur_exp_head->expense_head;
                        ?>
                        <option value="<?php echo $row->expense_head?>" selected><?php echo $cur_head?></option>
                        <?php
                            $get_heads = new selects();
                            $heads = $get_heads->fetch_details('expense_heads');
                            foreach($heads as $head){
                        ?>
                        <option value="<?php echo $head->exp_head_id?>"><?php echo $head->expense_head?></option>
                        <?php }?>
                    </select>
                </div>
                <div class="data" style="width:32%; margin:5px 0">
                    <label for="amount"> Amount</label>
                    <input type="text" name="amount" id="amount" value="<?php echo $row->amount?>">
                </div>
                <div class="data" style="width:60%; margin:5px 0">
                    <label for="details"> Description</label>
                    <textarea name="details" id="details" cols="30" rows="5"><?php echo $row->details?></textarea>
                </div>
                <div class="data" style="width:30%; margin:5px 0">
                    <button type="submit" id="post_exp" name="post_exp" onclick="updateExpense()">Update <i class="fas fa-save"></i></button>
                    <button style="background:brown; color:#fff; padding:8px; border-radius:5px"onclick="closeForm()">close <i class="fas fa-close"></i></button>
                </div>
            </div>
        </section>    
    </div>

<?php }}?>