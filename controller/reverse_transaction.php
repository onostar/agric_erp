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
                if($table->table_name == 'labour_payments'){
                    //get task id
                    $tasks = $check_column->fetch_details_group('labour_payments', 'task', 'trx_number', $trx_number);
                    foreach($tasks as $task){
                        $task_id = $task->task;
                    }
                    //update payment status on task table
                    $data = array(
                        'payment_status' => 0
                    );
                    $update_task = new Update_table();
                    $update_task->updateAny('tasks', $data, 'task_id', $task_id);
                }
                $delete_tx = new deletes();
                $delete_tx->delete_item($table->table_name, 'trx_number', $trx_number);
            }
            
        }
        
        
        echo "<div class='success'><p>Transaction reversed successfully! <i class='fas fa-thumbs-up'></i></p></div>";
    }
    ?>

    
    
