<?php

    if(isset($_GET['trx_number'])){
        $trx_number = $_GET['trx_number'];
        // $account = $_GET['account'];
       
        // instantiate class
        include "../classes/dbh.php";
        include "../classes/delete.php";
        include "../classes/select.php";
        include "../classes/update.php";
        
        
        //get all tables
        $get_tables = new selects();
        $tables = $get_tables->fetch_tables('tonnac_accounting');
        foreach($tables as $table){
            //check for transaction number column exist in each table and delete it when thenumber is seen
            $check_column = new selects();
            $cols = $check_column->fetch_column($table->table_name, 'trx_number');
            if($cols){
                $delete_tx = new deletes();
                $delete_tx->delete_item($table->table_name, 'trx_number', $trx_number);
            }
            
        }
        
        
        echo "<div class='success'><p>Transaction reversed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
    ?>

    
    
