<?php
    $id = htmlspecialchars(stripslashes($_POST['exp_id']));
    $date = htmlspecialchars(stripslashes($_POST['exp_date']));
    $head = htmlspecialchars(stripslashes(($_POST['exp_head'])));
    $amount = htmlspecialchars(stripslashes(($_POST['amount'])));
    $details = ucwords(htmlspecialchars(stripslashes(($_POST['details']))));

    // instantiate class
    include "../classes/dbh.php";
    include "../classes/select.php";
    include "../classes/update.php";

    //edit expense
    $update_expense = new Update_table();
    $update_expense->update_quadruple('expenses', 'expense_head', $head, 'amount', $amount, 'details', $details, 'expense_date', $date, 'expense_id', $id);
    if($update_expense){
        echo "<div class='succeed'><p>Expense Updated successfully!</p></div>";
    }