<?php
    date_default_timezone_set("Africa/Lagos");
    // session_start();
    class selects extends Dbh{
        //fetch all tables
        public function fetch_tables($database){
            $get_table = $this->connectdb()->prepare("SELECT table_name FROM information_schema.tables WHERE table_type = 'BASE TABLE' AND table_schema = '$database'");
            $get_table->execute();
            if($get_table->rowCount() > 0){
                $rows = $get_table->fetchAll();
                return $rows;
            }else{
                $rows = "<p class='no_result'>No Tables found</p>";
            }
        }
         //fetch details from any table order by
        public function fetch_details_order($table, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table ORDER BY $order");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;

            }else{
                $rows = "<p class='no_result'>No records found</p>";
            }
        }
        //check for columns
        public function fetch_column($table, $column){
            $get_user = $this->connectdb()->prepare("SHOW COLUMNS FROM $table LIKE '$column'");
            $get_user->execute();
            $rows = $get_user->fetch();
            return $rows;
        }
         //fetch details from any table
        public function fetch_details($table){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;

            }else{
                $rows = "<p class='no_result'>No records found</p>";
            }
        }
        //fetch last inserted from any table
        public function fetch_lastInserted($table, $column){
            $get_user = $this->connectdb()->prepare("SELECT $column FROM $table ORDER BY $column DESC LIMIT 1");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetch();
                return $rows;
            }else{
                $rows = "<p class='no_result'>No records found</p>";
            }
        }
        //fetch details with condition
        public function fetch_details_cond($table, $column, $condition){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column");
            $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with condition order by
        public function fetch_details_condOrder($table, $column, $condition, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column ORDER BY $order");
            $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with condition greater than
        public function fetch_details_greater($table, $column, $condition){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column > :$column");
            $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with condition = 2 likely condition
        public function fetch_details_eitherCon($table, $column, $val1, $val2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = $val1 OR $column = $val2");
            // $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with condition = 2 likely condition
        public function fetch_details_neitherCon($table, $column1, $val3, $column, $val1, $val2, $val4){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = :$column1 AND $column != $val1 AND $column != $val2 AND $column != $val4");
            $get_user->bindValue("$column1", $val3);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with different condition = 2 likely condition
        public function fetch_details_eitherCon2($table, $column1, $val1, $column2, $val2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = $val1 OR $column2 = $val2");
            // $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with different condition = 3 likely condition
        public function fetch_details_eitherCon3($table, $column1, $val1, $val2, $column2, $val3){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = $val1 OR $column1 = $val2 OR $column2 = $val3");
            // $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with like or close to
        public function fetch_details_like($table, $column, $condition){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column LIKE '%$condition%' LIMIT 30");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         //fetch item with quantity
        public function fetch_items_quantity($store, $item){
           $get_user = $this->connectdb()->prepare("SELECT  i.item_id, i.item_name, i.sales_price, IFNULL(SUM(inv.quantity), 0) AS quantity FROM items i LEFT JOIN inventory inv ON inv.item = i.item_id AND inv.store = :store WHERE i.item_name LIKE :item GROUP BY i.item_id ORDER BY i.item_name ASC LIMIT 30");
            $get_user->bindValue("store", $store);
            $get_user->bindValue("item", "%$item%");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         // fetch details like 3 option
         public function fetch_details_like3($table, $column1, $column2, $column3, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 LIKE '%$value%' OR $column2 LIKE '%$value%' OR $column3 LIKE '%$value%'");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with like or close to with a condition
        public function fetch_details_likeCond($table, $column, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column LIKE '%$value%'");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        // fetch details like 2 conditions
        public function fetch_details_like2Cond($table, $column1, $column2, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 LIKE '%$value%' OR $column2 LIKE '%$value%'");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        // fetch details like 2 conditions and 1 condition met
        public function fetch_details_like1Cond($table, $column1, $value, $condition, $cond_value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 LIKE '%$value%' AND $condition = :$condition");
            $get_user->bindValue("$condition", $cond_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        // fetch details like 2 conditions and 1 negative condition met
        public function fetch_details_likeNegCond($table, $column1, $value, $condition, $cond_value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 LIKE '%$value%' AND $condition != :$condition");
            $get_user->bindValue("$condition", $cond_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details count without condition
        public function fetch_count($table){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                
                return "0";
            }
        }
        //fetch details count with condition
        public function fetch_count_cond($table, $column, $condition){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column");
            $get_user->bindValue("$column", $condition);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch details count with 2 condition
        public function fetch_count_2cond($table, $column1, $condition1, $column2, $condition2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = :$column1 AND $column2 = :$column2");
            $get_user->bindValue("$column1", $condition1);
            $get_user->bindValue("$column2", $condition2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch details count with 2 condition and current date
        public function fetch_count_2condDate($table, $column1, $condition1, $column2, $condition2, $date){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = :$column1 AND $column2 = :$column2 AND date($date) = CURDATE()");
            $get_user->bindValue("$column1", $condition1);
            $get_user->bindValue("$column2", $condition2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch details count with 2 condition and current date and grouped by
        public function fetch_count_2condDateGro($table, $column1, $condition1, $column2, $condition2, $date, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = :$column1 AND $column2 = :$column2 AND date($date) = CURDATE() GROUP BY $group");
            $get_user->bindValue("$column1", $condition1);
            $get_user->bindValue("$column2", $condition2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch details count with condition and curdate
        public function fetch_count_curDate($table, $column){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE()");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        // select count with date and negative condition
        public function fetch_count_curDateCon($table, $column, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE() AND $condition != :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch with two condition
        public function fetch_details_2cond($table, $condition1, $condition2, $value1, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with three condition
        public function fetch_details_3cond($table, $condition1, $condition2, $con3, $value1, $value2, $val3){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2 AND $con3 = :$con3");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->bindValue("$con3", $val3);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with two condition group by
        public function fetch_details_2condGroup($table, $condition1, $condition2, $value1, $value2, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2 GROUP BY $group");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with one condition group by
        public function fetch_details_condGroup($table, $condition1, $value1, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 GROUP BY $group");
            $get_user->bindValue("$condition1", $value1);
            // $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with one condition group by
        public function fetch_AllStock(){
            $get_user = $this->connectdb()->prepare("SELECT SUM(DISTINCT quantity) AS total, cost_price, item FROM inventory GROUP BY item");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with one condition positiove and another negative group by
        public function fetch_details_2condNegGroup($table, $condition1, $condition2, $value1, $value2, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 != :$condition2 GROUP BY $group");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with one condition positive and another negative on curren dategroup by
        public function fetch_details_2condNegDateGroup($table, $condition1, $condition2, $value1, $value2, $date, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 != :$condition2 AND date($date) = CURDATE() GROUP BY $group");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with one condition positive and another negative on curren dategroup by
        public function fetch_details_2condNeg2DateGroup($table, $condition1, $condition2, $value1, $value2, $column, $from, $to, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 != :$condition2 AND $column BETWEEN '$from' AND '$to' GROUP BY $group");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with one condition positive and another negative between two dates
        public function fetch_details_2condNeg2Date($table, $condition1, $condition2, $value1, $value2, $column, $from, $to){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 != :$condition2 AND $column BETWEEN '$from' AND '$to'");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with two condition (one is negative)
        public function fetch_details_2cond1neg($table, $condition1, $condition2, $value1, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 != :$condition2");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with three condition (one is negative)
        public function fetch_details_3cond1neg($table, $condition1, $value1, $condition2, $value2, $condition3, $val3){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 != :$condition1 AND $condition2 = :$condition2 AND $condition3 = :$condition3");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->bindValue("$condition3", $val3);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates
        public function fetch_details_date($table, $condition1, $value1, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 BETWEEN '$value1' AND '$value2'");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and a condition
        public function fetch_details_date2Con($table, $column, $value1, $value2, $condition, $condition_value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND $column BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$condition",$condition_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and 2 condition
        public function fetch_details_2date2Con($table, $column, $value1, $value2, $condition, $condition_value, $condition2, $condition_value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND $condition2 = :$condition2 AND $column BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$condition",$condition_value);
            $get_user->bindValue("$condition2",$condition_value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and 1 positive condition and 1 negative condition
        public function fetch_details_2date1Con1Neg($table, $column, $value1, $value2, $condition, $condition_value, $condition2, $condition_value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND $condition2 != :$condition2 AND $column BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$condition",$condition_value);
            $get_user->bindValue("$condition2",$condition_value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and grouped
        public function fetch_details_dateGro($table, $condition1, $value1, $value2, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 BETWEEN '$value1' AND '$value2' GROUP BY $group");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and grouped order by
        public function fetch_details_dateGroOrder($table, $condition1, $value1, $value2, $group, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 BETWEEN '$value1' AND '$value2' GROUP BY $group ORDER BY $order");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and a condition and grouped
        public function fetch_details_dateGro1con($table, $condition1, $value1, $value2, $con, $con_value, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $con = :$con AND $condition1 BETWEEN '$value1' AND '$value2' GROUP BY $group");
            $get_user->bindValue("$con", $con_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and 2 condition and grouped
        public function fetch_details_dateGro2con($table, $condition1, $value1, $value2, $con, $con_value, $con2, $con_value2, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $con = :$con AND $con2 = :$con2 AND $condition1 BETWEEN '$value1' AND '$value2' GROUP BY $group");
            $get_user->bindValue("$con", $con_value);
            $get_user->bindValue("$con2", $con_value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and Condition
        public function fetch_details_2dateCon($table, $column, $condition1, $value1, $value2, $column_value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column AND $condition1 BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$column", $column_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and Condition grouped by 
        public function fetch_details_2dateConGr($table, $condition1, $value1, $value2, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE  $condition1 BETWEEN '$value1' AND '$value2' GROUP BY $group");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date
        public function fetch_details_curdate($table, $column){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE()");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date grouped by condition
        public function fetch_details_curdateGro($table, $column, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE() GROUP BY $group");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date grouped by condition order by
        public function fetch_details_curdateGroOrder($table, $column, $group, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE() GROUP BY $group ORDER BY $order");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date and a condition grouped by condition
        public function fetch_details_curdateGro1con($table, $column, $condition, $value, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE() AND $condition = :$condition GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date and 2 condition grouped by condition
        public function fetch_details_curdateGro2con($table, $column, $condition, $value, $condition2, $value2, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE() AND $condition = :$condition AND $condition2 = :$condition2 GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sums of certain column with current date grouped by condition
        public function fetch_details_curdateGroMany($table, $column4, $column1, $column2, $column3, $condition, $value, $group, $order){
            $get_user = $this->connectdb()->prepare("SELECT $column4, SUM($column1) AS column1, SUM($column2) AS column2 FROM $table WHERE date($column3) = CURDATE() AND $condition = :$condition GROUP BY $group ORDER BY $order DESC");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sums of certain column with current date by a condition grouped by another condition
        public function fetch_details_curdateGroMany1c($table, $column4, $column1, $column2, $column3, $condition, $value, $con2, $value2, $group, $order){
            $get_user = $this->connectdb()->prepare("SELECT $column4, SUM($column1) AS column1, SUM($column2) AS column2 FROM $table WHERE date($column3) = CURDATE() AND $condition = :$condition AND $con2 = :$con2 GROUP BY $group ORDER BY $order DESC");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$con2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sums of certain column with 2 date by a condition grouped by another condition
        public function fetch_details_2dateGroMany1c($table, $column4, $column1, $column2, $column3, $condition, $value, $con2, $value2, $group, $order, $from, $to){
            $get_user = $this->connectdb()->prepare("SELECT $column4, SUM($column1) AS column1, SUM($column2) AS column2 FROM $table WHERE date($column3) BETWEEN '$from' AND '$to' AND $condition = :$condition AND $con2 = :$con2 GROUP BY $group ORDER BY $order DESC");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$con2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date and condition
        public function fetch_details_curdateCon($table, $column, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND date($column) = CURDATE() ORDER BY $column");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date, one condition and negative condtion
        public function fetch_details_curdateCon1Neg($table, $column, $condition, $value, $neg, $neg_value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND $neg != :$neg AND date($column) = CURDATE()");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$neg", $neg_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with current date and condition
        public function fetch_details_curdate2Con($table, $column, $condition, $value, $con2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND $con2 = :$con2 AND date($column) = CURDATE()");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$con2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         //fetch all item grouped by a column
         public function fetch_single_grouped($table, $group){
            $get_details = $this->connectdb()->prepare("SELECT * FROM $table GROUP BY $group");
            $get_details->execute();
            if($get_details->rowCount() > 0){
                $row = $get_details->fetchAll();
                return $row;
            }else{
                $row = "No record found";
                return $row;
            }
        }
        //fetch sales order with current date
        public function fetch_salesOrder($store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(total_amount) AS total, invoice, posted_by, post_date FROM sales WHERE sales_status = 1 AND store = :store AND date(post_date) = CURDATE() GROUP BY invoice ORDER BY post_date DESC");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sales order from two selected date
        public function fetch_salesOrderDate($from, $to, $store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(total_amount) AS total, invoice, posted_by, post_date FROM sales WHERE sales_status = 1 AND store = :store AND date(post_date) BETWEEN '$from' AND '$to' GROUP BY invoice ORDER BY post_date");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch revenue by category with date
        public function fetch_revenue_cat($store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(sales.total_amount) AS total, SUM(sales.cost) AS total_cost, sales.item, items.item_id, items.cost_price, sales.quantity, items.department FROM sales, items WHERE sales.store = :store AND items.item_id = sales.item AND sales.sales_status = 2 AND date(sales.post_date) = CURDATE()GROUP BY items.department");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sales items in each revenue by category with current date
        public function fetch_revenue_cat_items($department, $store){
            $get_user = $this->connectdb()->prepare("SELECT sales.total_amount, sales.cost, sales.item, items.item_id, items.cost_price, items.item_name, sales.quantity, items.department, sales.invoice, sales.posted_by, sales.post_date FROM sales, items WHERE sales.store = :store AND items.department ='$department' AND items.item_id = sales.item AND sales.sales_status = 2 AND date(sales.post_date) = CURDATE()");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sales items in each revenue by category with 2 dates
        public function fetch_revenue_cat_itemsdate($from, $to, $department, $store){
            $get_user = $this->connectdb()->prepare("SELECT sales.total_amount, sales.cost, sales.item, items.item_id, items.cost_price, items.item_name, sales.quantity, items.department, sales.invoice, sales.posted_by, sales.post_date FROM sales, items WHERE sales.store = :store AND items.department ='$department' AND items.item_id = sales.item AND sales.sales_status = 2 AND date(sales.post_date) BETWEEN '$from' AND '$to'");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch revenue with date
        public function fetch_revenue($store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(sales.total_amount) AS total, SUM(sales.cost) AS total_cost, sales.item, items.item_id, items.cost_price, sales.quantity, items.department FROM sales, items WHERE sales.store = :store AND items.item_id = sales.item AND sales.sales_status = 2 AND date(sales.post_date) = CURDATE()");
            $get_user->bindValue('store', $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch revenue by category with 2 dates
        public function fetch_revenue_catDate($from, $to, $store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(sales.total_amount) AS total, SUM(sales.cost) AS total_cost, sales.item, items.item_id, items.cost_price, sales.quantity, items.department FROM sales, items WHERE sales.store = :store AND items.item_id = sales.item AND sales.sales_status = 2 AND date(sales.post_date) BETWEEN '$from' AND '$to' GROUP BY items.department");
            $get_user->bindValue('store', $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch revenue with 2 dates
        public function fetch_revenueDate($from, $to, $store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(sales.total_amount) AS total, SUM(sales.cost) AS total_cost, sales.item, items.item_id, items.cost_price, sales.quantity, items.department FROM sales, items WHERE sales.store = :store AND items.item_id = sales.item AND sales.sales_status = 2 AND date(sales.post_date) BETWEEN '$from' AND '$to'");
            $get_user->bindValue('store', $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with 2 condition
        public function fetch_sum_double($table, $column1, $condition, $value, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition = :$condition AND $condition2 = :$condition2");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
       
        //fetch sum with current date
        public function fetch_sum_curdate($table, $column1, $column2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE date($column2) = CURDATE()");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum
        public function fetch_sum($table, $column1){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with single equal condition
        public function fetch_sum_single($table, $column1, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with single greater than condition
        public function fetch_sum_greater($table, $column1, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition > :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with single lessthan condition
        public function fetch_sum_singleless($table, $column1, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition < :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied
        public function fetch_sum_2col($table, $column1, $column2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied and one condition
        public function fetch_sum_2colCond($table, $column1, $column2, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied and 2 condition
        public function fetch_sum_2col2Cond($table, $column1, $column2, $condition, $value, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE $condition = :$condition AND $condition2 = :$condition2");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of column multiplied and current date
        public function fetch_sum_2colCurDate($table, $column1, $column2, $date){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE date($date) = CURDATE()");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of column multiplied and current date with condition
        public function fetch_sum_2colCurDate1Con($table, $column1, $column2, $date, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE date($date) = CURDATE()AND $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of column multiplied and current date with condition grouped
        public function fetch_sum_2colCurDate1ConGroup($table, $column1, $column2, $date, $condition, $value, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM(DISTINCT $column1 * $column2) AS total FROM $table WHERE date($date) = CURDATE() AND $condition = :$condition GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of column multiplied and current date with condition
        public function fetch_sum_2colCurDate2Con($table, $column1, $column2, $date, $condition, $value, $con2, $con_val2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE date($date) = CURDATE()AND $condition = :$condition AND $con2 = :$con2");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$con2", $con_val2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied with 2 dates
        public function fetch_sum_2col2date($table, $column1, $column2, $date, $from, $to){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE date($date) BETWEEN '$from' AND '$to'");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied with 2 dates and a condition
        public function fetch_sum_2col2date1con($table, $column1, $column2, $date, $from, $to, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE $condition = :$condition AND date($date) BETWEEN '$from' AND '$to'");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied with 2 dates and a condition group by
        public function fetch_sum_2col2date1congroup($table, $column1, $column2, $date, $from, $to, $condition, $value, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM( distinct $column1 * $column2) AS total FROM $table WHERE $condition = :$condition AND date($date) BETWEEN '$from' AND '$to' GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns multiplied with 2 dates and 2 condition
        public function fetch_sum_2col2date2con($table, $column1, $column2, $date, $from, $to, $condition, $value, $con2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE $condition = :$condition AND $con2 = :$con2 AND date($date) BETWEEN '$from' AND '$to'");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$con2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with conditions
        public function fetch_sum_2con($table, $column1, $column2, $condition1, $condition2, $value1, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with a condition
        public function fetch_sum_con($table, $column1, $column2, $condition1, $value1){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) AS total FROM $table WHERE $condition1 = :$condition1");
            $get_user->bindValue("$condition1", $value1);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current date AND condition
        public function fetch_sum_curdateCon($table, $column1, $column2, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition =:$condition AND date($column2) = CURDATE()");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current date AND condition
        public function fetch_sum_curdateConGroup($table, $column1, $column2, $condition, $value, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition =:$condition AND date($column2) = CURDATE() GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current date AND 2 condition
        public function fetch_sum_curdate2ConGroup2($table, $column1, $column2, $condition, $value, $con2, $val2, $group, $group2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition =:$condition AND $con2 = :$con2 AND date($column2) = CURDATE() GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$con2", $val2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of waybill based on current date grouped by invoice and vendor
        public function fetch_curdateWaybill($store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(waybill) AS total FROM (SELECT invoice, MAX(waybill) AS waybill FROM purchases WHERE store = :store_id AND purchase_status = 0 AND DATE(post_date) = CURDATE() GROUP BY invoice) AS unique_invoices");
            $get_user->bindValue("store_id", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of waybill based on 2 dates grouped by invoice and vendor
        public function fetch_curdateWaybillDates($from, $to, $store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(waybill) AS total FROM (SELECT invoice, MAX(waybill) AS waybill FROM purchases WHERE store = :store_id AND purchase_status = 0 AND DATE(post_date) BETWEEN '$from' AND '$to' GROUP BY invoice) AS unique_invoices");
            $get_user->bindValue("store_id", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        
        //fetch sum with current date AND condition
        public function fetch_sum_curdateDistinctConGroup($table, $column1, $column2, $condition, $value, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM(amount) AS total FROM (SELECT MAX($column1) AS amount FROM $table WHERE $condition = :$condition AND DATE($column2) = CURDATE() GROUP BY $group) AS unique_invoices;");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with 2 condition with 1 negative
        public function fetch_sum_double1Neg($table, $column1, $condition, $value, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition = :$condition AND $condition2 != :$condition2");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         //fetch account balance
         public function fetch_vendor_balance($account){
            $get_user = $this->connectdb()->prepare("SELECT SUM(credit - debit) AS balance, account FROM transactions WHERE account = :account");
            $get_user->bindValue('account', $account);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current date AND 2 condition
        public function fetch_sum_curdate2Con($table, $column1, $column2, $condition1, $value1, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition1 =:$condition1 AND $condition2 =:$condition2 AND date($column2) = CURDATE()");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current date AND 3 condition
        public function fetch_sum_curdate3Con($table, $column1, $column2, $condition1, $value1, $condition2, $value2, $condition3, $value3){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition1 =:$condition1 AND $condition2 =:$condition2 AND $condition3 = :$condition3 AND date($column2) = CURDATE()");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->bindValue("$condition3", $value3);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current date AND 2 condition grouped by
        public function fetch_sum_curdate2ConGro($table, $column1, $column2, $condition1, $value1, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total, posted_by, payment_mode FROM $table WHERE $condition1 =:$condition1 AND date($column2) = CURDATE() GROUP BY $group");
            $get_user->bindValue("$condition1", $value1);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum between date
        //fetch between two dates
        public function fetch_sum_2date($table, $column, $condition1, $value1, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column) as total FROM $table WHERE $condition1 BETWEEN '$value1' AND '$value2'");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum between two dates and condition
        public function fetch_sum_2dateCond($table, $column1, $column2, $condition1, $value1, $value2, $value3){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE $column2 = :$column2 AND $condition1 BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$column2", $value3);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum between two dates and  2 condition
        public function fetch_sum_2date2Cond($table, $column1, $column2, $condition1, $condition2, $value1, $value2, $value3, $value4){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2 AND $column2 BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$condition1", $value3);
            $get_user->bindValue("$condition2", $value4);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum between two dates and  3 condition
        public function fetch_sum_2date3Cond($table, $column1, $column2, $condition1, $condition2, $condition3,  $value1, $value2, $value3, $value4, $value5){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2 AND $condition3 = :$condition3 AND $column2 BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$condition1", $value3);
            $get_user->bindValue("$condition2", $value4);
            $get_user->bindValue("$condition3", $value5);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch expired item with condition
        function fetch_expired($table, $column, $quantity, $condition, $value){
            $get_exp = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND date($column) <= CURDATE() AND $quantity >= 1");
            $get_exp->bindValue("$condition", $value);
            $get_exp->execute();

            if($get_exp->rowCount() > 0){
                return $get_exp->rowCount();
            }else{
                return "0";
            }
        }
        //fetch expired item details
        function fetch_expired_det($table, $column, $quantity, $condition, $value){
            $get_exp = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND date($column) <= CURDATE() AND $quantity >= 1");
            $get_exp->bindValue("$condition", $value);
            $get_exp->execute();

            if($get_exp->rowCount() > 0){
                $rows = $get_exp->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch soon to expire item
        function fetch_expire_soon($table, $column, $quantity, $condition, $value){
            $get_exp = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND $quantity >= 1 AND date($column) BETWEEN CURDATE() AND CURDATE() + INTERVAL 3 MONTH");
            $get_exp->bindValue("$condition", $value);
            $get_exp->execute();

            if($get_exp->rowCount() > 0){
                return $get_exp->rowCount();
            }else{
                return "0";
            }
        }
        //fetch soon to expire item details
        function fetch_expire_soon_det($table, $column, $quantity, $condition, $value){
            $get_exp = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND $quantity >= 1 AND date($column) BETWEEN CURDATE() AND CURDATE() + INTERVAL 3 MONTH");
            $get_exp->bindValue("$condition", $value);
            $get_exp->execute();

            if($get_exp->rowCount() > 0){
                $rows = $get_exp->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch soon to expire item sum
        function fetch_expire_soonSum($table, $column, $column2, $column3, $condition, $value){
            $get_exp = $this->connectdb()->prepare("SELECT SUM($column2 * $column3) AS total FROM $table WHERE $condition = :$condition AND date($column) BETWEEN CURDATE() AND CURDATE() + INTERVAL 3 MONTH");
            $get_exp->bindValue("$condition", $value);
            $get_exp->execute();

            if($get_exp->rowCount() > 0){
                $rows = $get_exp->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch soon to expire item sum
        function fetch_expired_Sum($table, $column, $column2, $column3, $condition, $value){
            $get_exp = $this->connectdb()->prepare("SELECT SUM($column2 * $column3) AS total FROM $table WHERE $condition = :$condition AND date($column) <= CURDATE()");
            $get_exp->bindvalue("$condition", $value);
            $get_exp->execute();

            if($get_exp->rowCount() > 0){
                $rows = $get_exp->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch items lesser than a value
        function fetch_lesser($table, $column, $value){
            $get_item = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column <= $value");
            $get_item->execute();

            if($get_item->rowCount() > 0){
                return $get_item->rowCount();
            }else{
                return "0";
            }
        }
        //fetch items lesser than a value from 2 tables with condition
        function fetch_lesser_cond($table, $column, $value, $condition, $condition_value){
            $get_item = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND $column <= $value");
            $get_item->bindValue("$condition", $condition_value);
            $get_item->execute();

            if($get_item->rowCount() > 0){
                return $get_item->rowCount();
            }else{
                return "0";
            }
        }
        //fetch items lesser than a value details
        function fetch_lesser_detail($table, $column, $value){
            $get_item = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column <= $value");
            $get_item->execute();

            if($get_item->rowCount() > 0){
                $rows = $get_item->fetchAll();
                return $rows;
            }else{
                $rows = "No record found";
                return $rows;
            }
        }
        //fetch items lesser than a value with condition details
        function fetch_lesser_detailCond($table, $column, $value, $condition, $cond_value){
            $get_item = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition AND $column <= $value");
            $get_item->bindValue("$condition", $cond_value);
            $get_item->execute();

            if($get_item->rowCount() > 0){
                $rows = $get_item->fetchAll();
                return $rows;
            }else{
                $rows = "No record found";
                return $rows;
            }
        }
        //fetch items lesser than a value details
        function fetch_lesser_sum($table, $column, $value, $column1, $column2){
            $get_item = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) as total FROM $table WHERE $column <= $value");
            $get_item->execute();

            if($get_item->rowCount() > 0){
                $rows = $get_item->fetchAll();
                return $rows;
            }else{
                $rows = "No record found";
                return $rows;
            }
        }
        function fetch_lesser_sumCon($table, $column, $value, $condition, $con_value, $column1, $column2){
            $get_item = $this->connectdb()->prepare("SELECT SUM($column1 * $column2) as total FROM $table WHERE $condition = :$condition AND $column <= $value");
            $get_item->bindValue("$condition", $con_value);
            $get_item->execute();

            if($get_item->rowCount() > 0){
                $rows = $get_item->fetchAll();
                return $rows;
            }else{
                $rows = "No record found";
                return $rows;
            }
        }
        //fetch sum between two dates and condition grouped by
        public function fetch_sum_2dateCondGr($table, $column1, $column2, $condition1, $condition2, $value1, $value2, $value3, $value4){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE $column2 = :$column2 AND $condition2 = :$condition2 and $condition1 BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$column2", $value3);
            $get_user->bindValue("$condition2", $value4);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum between two dates and condition grouped by
        public function fetch_sum_2date1CondGr($table, $column1, $column2, $condition1, $value1, $value2, $value3, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM(amount) AS total FROM (SELECT MAX($column1) AS amount FROM $table WHERE $column2 = :$column2 AND DATE($condition1) BETWEEN '$value1' AND '$value2' GROUP BY $group) AS unique_invoices;");
            $get_user->bindValue("$column2", $value3);
            // $get_user->bindValue("$condition2", $value4);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with negative condition
        public function fetch_details_negCond1($table, $column1, $value1){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 != :$column1");
            $get_user->bindValue("$column1", $value1);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with condition less than a value
        public function fetch_details_lessthan($table, $column1, $value1){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 < :$column1");
            $get_user->bindValue("$column1", $value1);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        
        //fetch details with negative condition and a positive
        public function fetch_details_negCond($table, $column1, $value1, $column2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 != :$column1 AND $column2 = :$column2");
            $get_user->bindValue("$column1", $value1);
            $get_user->bindValue("$column2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with negative condition or a positive
        public function fetch_details_negOrCond($table, $column1, $value1, $column2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 != :$column1 OR $column2 = :$column2");
            $get_user->bindValue("$column1", $value1);
            $get_user->bindValue("$column2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with date condition
        public function fetch_details_dateCond($table, $condition1, $value1){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND date(check_out_date) = CURDATE()");
            $get_user->bindValue("$condition1", $value1);
            // $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details with date and 2 conditions
        public function fetch_details_date2Cond($table, $column, $condition1, $value1, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 = :$condition2 AND $column = CURDATE()");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            // $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch item history
        public function fetch_item_history($from, $to, $value3, $store){
            $get_history = $this->connectdb()->prepare("SELECT * FROM audit_trail WHERE item = :item AND store = :store AND date(post_date) BETWEEN '$from' AND '$to' ORDER BY DATE(post_date) ASC");
            $get_history->bindValue("item", $value3);
            $get_history->bindValue("store", $store);
            $get_history->execute();
            if($get_history->rowCount() > 0){
                $rows = $get_history->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        // fetch todays check in
        public function fetch_checkIn($table, $condition1, $condition2, $value1){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition1 = :$condition1 AND $condition2 = CURDATE()");
            $get_user->bindValue("$condition1", $value1);
            // $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch details count with 2 condition
        public function check_dep($asset, $year){
            $get_user = $this->connectdb()->prepare("SELECT * FROM depreciation WHERE asset = :asset AND YEAR(post_date) = $year");
            $get_user->bindValue("asset", $asset);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        
         //fetch details count with 3 condition
         public function fetch_count_3cond($table, $column1, $condition1, $column2, $condition2, $column3, $val3){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = :$column1 AND $column2 = :$column2 AND $column3 = :$column3");
            $get_user->bindValue("$column1", $condition1);
            $get_user->bindValue("$column2", $condition2);
            $get_user->bindValue("$column3", $val3);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch single column details with single condition grouped
        public function fetch_details_group($table, $column, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT $column FROM $table WHERE $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $row = $get_user->fetch();
                return $row;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch all details with 1 condition grouped
        public function fetch_details_Allgroup($table, $condition, $value, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition = :$condition GROUP BY $group");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $row = $get_user->fetch();
                return $row;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        
        // fetch daily sales
        public function fetch_daily_sales($store){
            $get_daily = $this->connectdb()->prepare("SELECT COUNT(distinct invoice) AS customers, SUM(total_amount) AS revenue, post_date FROM sales WHERE store = :store AND sales_status = 2 GROUP BY date(post_date) ORDER BY date(post_date) DESC");
            $get_daily->bindValue('store', $store);
            $get_daily->execute();
            if($get_daily->rowCount() > 0){
                $rows = $get_daily->fetchAll();
                return $rows;

            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        // fetch daily trial balance
        public function fetch_trial_balance(){
            $get_daily = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits, post_date, account, account_type FROM transactions WHERE date(post_date) = CURDATE() AND trx_status = 0 GROUP BY account DESC");
            // $get_daily->bindValue('store', $store);
            $get_daily->execute();
            if($get_daily->rowCount() > 0){
                $rows = $get_daily->fetchAll();
                return $rows;

            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        // fetch trial balance by date range
        public function fetch_trial_balanceDate($from, $to){
            $get_daily = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits, post_date, account, account_type FROM transactions WHERE date(post_date) BETWEEN '$from' AND '$to' AND trx_status = 0 GROUP BY account DESC");
            // $get_daily->bindValue('store', $store);
            $get_daily->execute();
            if($get_daily->rowCount() > 0){
                $rows = $get_daily->fetchAll();
                return $rows;

            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with yearly condition group by
        public function fetch_details_yearlyGroup($table, $condition1, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE YEAR($condition1) = YEAR(CURDATE())GROUP BY $group");
            // $get_user->bindValue("$condition1", $value1);
            // $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch with a specific selected year group
        public function fetch_details_specYearGro($table, $full_date, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE YEAR(post_date) = YEAR('$full_date') GROUP BY $group");
           
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current month
        public function fetch_sum_curMonth($table, $column1, $column2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE MONTH($column2) = MONTH(CURDATE()) AND YEAR($column2) = YEAR(CURDATE())");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current month
        public function fetch_sum_curMonth1con($table, $column1, $column2, $con, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $con = :$con AND MONTH($column2) = MONTH(CURDATE()) AND YEAR($column2) = YEAR(CURDATE())");
            $get_user->bindValue("$con", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current month and 1 negative condition
        public function fetch_sum_curMonth1con1neg($table, $column1, $column2, $con, $value, $negCon, $negValue){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $con = :$con AND $negCon != :$negCon AND MONTH($column2) = MONTH(CURDATE()) AND YEAR($column2) = YEAR(CURDATE())");
            $get_user->bindValue("$negCon", $negValue);
            $get_user->bindValue("$con", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum with current month
        public function fetch_sum_curMonth2con($table, $column1, $column2, $con, $value, $con2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $con = :$con AND $con2 = :$con2 AND MONTH($column2) = MONTH(CURDATE()) AND YEAR($column2) = YEAR(CURDATE())");
            $get_user->bindValue("$con", $value);
            $get_user->bindValue("$con2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         //fetch data and grouped
         public function fetch_details_groupBy($table, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table GROUP BY $group");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch debtors
        public function fetch_debtors(){
            $get_user = $this->connectdb()->prepare("SELECT SUM(debit - credit) AS total_due, account FROM transactions WHERE class = 4 GROUP BY account HAVING SUM(debit) - SUM(credit) > 0");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        
        //fetch monthly financial position
        public function fetch_monthly_pos($cond, $value, $month, $year){
            $get_user = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits FROM transactions WHERE MONTH(post_date) = $month AND YEAR(post_date) = $year AND $cond = :$cond");
            $get_user->bindValue("$cond", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch yearly financial position
        public function fetch_yearly_pos($cond, $value, $year){
            $get_user = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits FROM transactions WHERE YEAR(post_date) = $year AND $cond = :$cond");
            $get_user->bindValue("$cond", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         //fetch sum for a specific month and year and a condition
         public function fetch_sum_monthYearCond($table, $column1, $column2, $month, $year, $con, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE MONTH($column2) = $month AND YEAR($column2) = $year AND $con = :$con");
            $get_user->bindValue("$con", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum for a specific year with no condition
        public function fetch_sum_Year($table, $column1, $column2, $year){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE YEAR($column2) = $year");
            // $get_user->bindValue("$con", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum for a specific year and a condition
        public function fetch_sum_YearCond($table, $column1, $column2, $year, $con, $value){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE YEAR($column2) = $year AND $con = :$con");
            $get_user->bindValue("$con", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum for a specific year and 2 condition
        public function fetch_sum_Year2Cond($table, $column1, $column2, $year, $con, $value, $con2, $val2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE YEAR($column2) = $year AND $con = :$con AND $con2 = :$con2");
            $get_user->bindValue("$con", $value);
            $get_user->bindValue("$con2", $val2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
         //fetch monthly account statement
         public function fetch_monthlyStatement($account, $month, $year){
            $get_user = $this->connectdb()->prepare("SELECT * FROM transactions WHERE MONTH(post_date) = $month AND YEAR(post_date) =  $year AND account = :account /* GROUP BY trx_number */ ORDER BY post_date");
            $get_user->bindValue("account", $account);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch yearly account statement
        public function fetch_yearlyStatement($account, $year){
            $get_user = $this->connectdb()->prepare("SELECT * FROM transactions WHERE YEAR(post_date) =  $year AND account = :account /* GROUP BY trx_number */ ORDER BY post_date");
            $get_user->bindValue("account", $account);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of a columns with current date AND 2 condition 1 negative
        public function fetch_sum_curdate2Con1neg($table, $column1, $column2, $condition1, $value1, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition1 =:$condition1 AND $condition2 !=:$condition2 AND date($column2) = CURDATE()");
            $get_user->bindValue("$condition1", $value1);
            $get_user->bindValue("$condition2", $value2);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch pending itemson purchaseo rder
        public function fetch_pending_items($store, $invoice){
            $get_user = $this->connectdb()->prepare("SELECT SUM(quantity - supplied) AS total FROM purchase_order WHERE order_status = 1 AND invoice = :invoice AND store = :store");
            $get_user->bindValue("invoice", $invoice);
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch between two dates and Condition order
        public function fetch_details_2dateConOrder($table, $column, $condition1, $value1, $value2, $column_value, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column AND $condition1 BETWEEN '$value1' AND '$value2' ORDER BY $order");
            $get_user->bindValue("$column", $column_value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum between two dates and  2 condition with 1 negative
        public function fetch_sum_2date2Cond1neg($table, $column1, $column2, $condition1, $condition2, $value1, $value2, $value3, $value4){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE $condition1 = :$condition1 AND $condition2 != :$condition2 AND $column2 BETWEEN '$value1' AND '$value2'");
            $get_user->bindValue("$condition1", $value3);
            $get_user->bindValue("$condition2", $value4);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch payables
        public function fetch_payables(){
            $get_user = $this->connectdb()->prepare("SELECT COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) AS total_due account FROM transactions WHERE class = 7 GROUP BY account HAVING total_due > 0");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch receivables
        public function fetch_receivables($store){
            $get_user = $this->connectdb()->prepare("SELECT COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) AS total_due FROM transactions WHERE class = '4' AND store = :store");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch negative account balance
        public function fetch_account_balance($account){
            $get_user = $this->connectdb()->prepare("SELECT COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) AS balance, account FROM transactions WHERE account = :account");
            $get_user->bindValue('account', $account);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
       /*  //fetch positive account balance
        public function fetch_positive_balance($account){
            $get_user = $this->connectdb()->prepare("SELECT SUM(credit - debit) AS balance, account FROM transactions WHERE account = :account");
            $get_user->bindValue('account', $account);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        } */
        // fetch daily credit sales
        public function fetch_daily_credit($store){
            $get_daily = $this->connectdb()->prepare("SELECT COUNT(distinct invoice) AS customers, SUM(amount_paid) AS revenue, post_date FROM payments WHERE store = :store GROUP BY date(post_date) ORDER BY date(post_date) DESC");
            $get_daily->bindValue('store', $store);
            $get_daily->execute();
            if($get_daily->rowCount() > 0){
                $rows = $get_daily->fetchAll();
                return $rows;

            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch monthly sales
        public function fetch_monthly_sales($store){
            $get_monthly = $this->connectdb()->prepare("SELECT COUNT(distinct invoice) AS customers, SUM(total_amount) AS revenue, post_date, COUNT(post_date) AS arrivals, COUNT(DISTINCT date(post_date)) AS daily_average FROM sales WHERE store = :store AND sales_status = 2 GROUP BY MONTH(post_date) ORDER BY MONTH(post_date)");
            $get_monthly->bindValue('store', $store);
            $get_monthly->execute();
            if($get_monthly->rowCount() > 0){
                $rows = $get_monthly->fetchAll();
                return $rows;

            }else{
                $rows = "No records found";
                return $rows;
            }
        }

    //update value with condion
        
    }    
    //controller for user details
    /* class user_detailsController extends user_details{
        private $table;
        private $condition;

        public function __construct($table, $condition){
            $this->table = $table;
            $this->condition = $condition;
        }

        public function get_user(){
            return $this->fetch_details($this->table);
        }
        public function get_user_cond(){
            return $this->fetch_details_cond($this->table, $this->condition);

        }
    } */

