<?php

    if(isset($_GET['expense_id'])){
        $expense = $_GET['expense_id'];
        // $customer = $_GET['customer'];
        
        // instantiate class
        include "../classes/dbh.php";
        include "../classes/delete.php";


        //update wallet balance
        
        
            //delete reversal
            $delete_deposit = new deletes();
            $delete_deposit->delete_item('expenses', 'expense_id', $expense);
    ?>
        
<?php
    echo "<div class='success'><p>Expense reversed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
        }
    
