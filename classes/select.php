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
        //fetch last inserted from any table with a condition
        public function fetch_lastInsertedCon($table, $column, $column2, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column2 = :$column2 ORDER BY $column DESC LIMIT 1");
            $get_user->bindValue("$column2", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "<p class='no_result'>No records found</p>";
            }
        }
        //fetch details from any table with condition
        public function fetch_details_pageCondOrder($table, $column, $value, $limit, $offset, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column ORDER BY $order DESC LIMIT $offset, $limit");
            $get_user->bindValue("$column", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;

            }else{
                $rows = "<p class='no_result'>No records found</p>";
            }
        }

        //fetch with current date less than condition and 2 condition 
        public function fetch_details_curdategreater2con($table, $column, $condition, $value, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) <= date(CURDATE()) AND $condition = :$condition AND $condition2 = :$condition2");
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
        //fetch with current date less than condition and 2 condition 
        public function fetch_due_payments($table, $date, $store){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date(CURDATE()) >= date($date) AND store = :store AND payment_status = 0");
            $get_user->bindValue(":store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch sum of 2 columns with current date AND 2 condition
        public function fetch_sum_curdategreater2Con($table, $column1, $column2, $condition1, $value1, $condition2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition1 =:$condition1 AND $condition2 =:$condition2 AND date($column2) <= CURDATE()");
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
        //fetch details with condition and a limit descending order
        public function fetch_details_condLimitDesc($table, $column, $condition, $limit, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column ORDER BY $order DESC LIMIT $limit");
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
        //fetch details with condition and a limit ascending order
        public function fetch_details_condLimitAsc($table, $column, $condition, $limit, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column ORDER BY $order ASC LIMIT $limit");
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
        //fetch details with 2 condition and a limit ascending order
        public function fetch_details_2condLimitAsc($table, $column, $condition, $con2, $val2, $limit, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column AND $con2 = :$con2 ORDER BY $order ASC LIMIT $limit");
            $get_user->bindValue("$column", $condition);
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
         //fetch details with 2 condition order by
        public function fetch_details_2condOrder($table, $con1, $con2, $value1, $value2, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $con1 = :$con1 AND $con2 = :$con2 ORDER BY $order");
            $get_user->bindValue("$con1", $value1);
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
           $get_user = $this->connectdb()->prepare("SELECT i.item_id, i.item_name, i.sales_price, IFNULL(SUM(inv.quantity), 0) AS quantity FROM items i LEFT JOIN inventory inv ON inv.item = i.item_id AND inv.store = :store WHERE i.item_name LIKE :item GROUP BY i.item_id ORDER BY i.item_name ASC LIMIT 30");
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
         //fetch item price
        public function fetch_item_price($store){
           $get_user = $this->connectdb()->prepare("SELECT i.item_id, i.item_name, IFNULL(p.cost, 0) AS cost_price,  IFNULL(p.sales_price, 0) AS sales_price, IFNULL(p.other_price, 0) AS other_price FROM items i LEFT JOIN prices p ON p.item = i.item_id AND p.store = :store ORDER BY i.item_name");
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
        // fetch details like 3 conditions
        public function fetch_details_like3Cond($table, $column1, $column2, $column3, $value){
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
        //fetch last inserted from any table with a condition ascending order
        public function fetch_lastInsertedConAsc($table, $column, $column2, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column2 = :$column2 ORDER BY $column ASC LIMIT 1");
            $get_user->bindValue("$column2", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "<p class='no_result'>No records found</p>";
            }
        }
        // fetch details like 3 column and 1 condition
        public function fetch_details_like3Col1Con($table, $column1, $column2, $column3, $value, $con, $conVal){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 LIKE '%$value%' OR $column2 LIKE '%$value%' OR $column3 LIKE '%$value%' AND $con = :$con LIMIT 20");
            $get_user->bindValue("$con", "$conVal");
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch staffs currently on leave
        public function fetch_current_leave($store){
            $get_user = $this->connectdb()->prepare("SELECT * FROM leaves WHERE store = :store AND leave_status = 1 AND start_date <= CURDATE()");
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
        //fetch attendance report for current day
        public function fetch_attendance($store){
            $get_user = $this->connectdb()->prepare("SELECT s.staff_id, s.last_name, s.other_names, s.staff_number, s.department, s.designation, a.time_in, a.time_out, a.marked_by, a.checked_out_by, a.marked_date, a.checked_out, a.attendance_status, a.location, a.longitude, a.latitude, a.attendance_id, CASE WHEN a.time_in IS NOT NULL AND a.time_out IS NULL THEN 'Not Signed Out' WHEN a.time_in IS NOT NULL AND a.time_out IS NOT NULL THEN 'Present' WHEN l.leave_status = 1 THEN 'On Leave' ELSE 'Absent' END AS status FROM staffs s LEFT JOIN attendance a  ON s.staff_id = a.staff AND DATE(a.attendance_date) = CURDATE()LEFT JOIN leaves l ON s.staff_id = l.employee AND l.leave_status = 1 AND CURDATE() BETWEEN l.start_date AND l.end_date WHERE s.store = :store ORDER BY s.last_name ASC;");
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
        //fetch attendance report by date
        /* public function fetch_attendance_date($from, $to, $store){
            $sql = "SELECT s.staff_id, s.last_name, s.other_names, s.staff_number, s.department, s.designation, a.time_in, a.time_out, a.marked_by, a.checked_out_by, a.marked_date, a.checked_out, a.attendance_status, a.attendance_date, a.location, a.latitude, a.longitude, CASE WHEN a.time_in IS NOT NULL AND a.time_out IS NULL THEN 'Not Signed Out' WHEN a.time_in IS NOT NULL AND a.time_out IS NOT NULL THEN 'Present' WHEN l.leave_status = 1 THEN 'On Leave' ELSE 'Absent' END AS status FROM staffs s LEFT JOIN attendance a  ON s.staff_id = a.staff AND DATE(a.attendance_date) BETWEEN :from AND :to LEFT JOIN leaves l ON s.staff_id = l.employee AND l.leave_status = 1 AND (l.start_date BETWEEN :from AND :to OR l.end_date BETWEEN :from AND :to OR (:from BETWEEN l.start_date AND l.end_date)) WHERE s.store = :store ORDER BY a.attendance_date ASC";
            $stmt = $this->connectdb()->prepare($sql);
            $stmt->bindValue(":store", $store);
            $stmt->bindValue(":from", $from);
            $stmt->bindValue(":to", $to);
            $stmt->execute();

            if($stmt->rowCount() > 0){
                return $stmt->fetchAll();
            } else {
                return "No records found";
            }
        } */
 public function fetch_attendance_date($from, $to, $store){
    $sql = "
    WITH RECURSIVE dates AS (
        SELECT DATE(:from) AS report_date
        UNION ALL
        SELECT DATE_ADD(report_date, INTERVAL 1 DAY)
        FROM dates
        WHERE report_date < DATE(:to)
    )
    SELECT 
        s.staff_id,
        s.last_name,
        s.other_names,
        s.staff_number,
        s.department,
        s.designation,

        d.report_date AS attendance_date,

        a.time_in,
        a.time_out,
        a.marked_by,
        a.checked_out_by,
        a.marked_date,
        a.checked_out,
        a.location,
        a.latitude,
        a.longitude,

        CASE
            WHEN l.leave_status = 1 THEN 'On Leave'
            WHEN a.time_in IS NOT NULL AND a.time_out IS NULL THEN 'Not Signed Out'
            WHEN a.time_in IS NOT NULL AND a.time_out IS NOT NULL THEN 'Present'
            ELSE 'Absent'
        END AS attendance_status

    FROM staffs s
    CROSS JOIN dates d

    LEFT JOIN attendance a 
        ON a.staff = s.staff_id 
       AND DATE(a.attendance_date) = d.report_date

    LEFT JOIN leaves l 
        ON l.employee = s.staff_id
       AND l.leave_status = 1
       AND d.report_date BETWEEN l.start_date AND l.end_date

    WHERE s.store = :store

    ORDER BY d.report_date ASC, s.last_name ASC
    ";

    $stmt = $this->connectdb()->prepare($sql);
    $stmt->bindValue(':store', $store);
    $stmt->bindValue(':from', $from);
    $stmt->bindValue(':to', $to);
    $stmt->execute();

    return $stmt->rowCount() > 0 ? $stmt->fetchAll() : "No records found";
}
        //fetch active staff for attendance
        public function fetch_staff_attendance($store){
            $get_user = $this->connectdb()->prepare("SELECT 
                s.staff_id,
                s.last_name, 
                s.other_names, 
                s.staff_number, 
                s.department, 
                s.designation, 
                s.gender
            FROM staffs s
            LEFT JOIN leaves l 
                ON s.staff_id = l.employee 
                AND l.leave_status = 1 
                AND CURDATE() BETWEEN l.start_date AND l.end_date
            LEFT JOIN attendance a
                ON s.staff_id = a.staff 
                AND DATE(a.attendance_date) = CURDATE()
            WHERE 
                s.store = :store  
                AND s.staff_status = 0
                AND l.employee IS NULL
                AND a.staff IS NULL
            ORDER BY s.last_name ASC");
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
        //fetch total staffs present at work
        public function fetch_staff_present($store){
            $get_user = $this->connectdb()->prepare("SELECT * FROM attendance WHERE store = :store AND DATE(attendance_date) = CURDATE()");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                
                return 0;
            }
        }
        //fetch total staffs late at work
        public function fetch_late_arrivals($store){
            $get_user = $this->connectdb()->prepare("SELECT * FROM attendance WHERE store = :store AND DATE(attendance_date) = CURDATE() AND time_in > '8:05:00'");
            $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                
                return 0;
            }
        }
       
        //check if staff already marked attendance for the day
        public function check_attendance($staff){
            $get_user = $this->connectdb()->prepare("SELECT * FROM attendance WHERE staff = :staff AND DATE(attendance_date) = CURDATE()");
            $get_user->bindValue("staff", $staff);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return 0;
            }
        }
        //check if staff has checked out already for the day
        public function chEck_checkout($staff){
            $get_user = $this->connectdb()->prepare("SELECT * FROM attendance WHERE staff = :staff AND DATE(attendance_date) = CURDATE() AND time_out IS NOT NULL");
            $get_user->bindValue("staff", $staff);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return 0;
            }
        }
        //fetch salary structure
        public function fetch_salary_structure($store){
            $get_user = $this->connectdb()->prepare("SELECT 
                s.staff_id,
                s.last_name, 
                s.other_names, 
                s.staff_number, 
                s.department, 
                s.designation, 
                s.gender, sa.basic_salary, sa.utility_allowance, sa.housing_allowance, sa.medical_allowance, sa.transport_allowance, sa.other_allowance, sa.total_earnings, sa.salary_id
            FROM staffs s
            LEFT JOIN salary_structure sa
                ON s.staff_id = sa.staff 
            WHERE 
                s.store = :store  
                AND s.staff_status = 0
            ORDER BY s.last_name ASC");
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
        //fetch staffs to generate pay roll
        public function fetch_generate_payroll($store){
            $get_user = $this->connectdb()->prepare("SELECT s.staff_id, s.last_name, s.other_names, s.staff_number, s.department, s.designation, s.gender, ss.basic_salary, ss.housing_allowance, ss.transport_allowance, ss.utility_allowance, ss.medical_allowance, ss.other_allowance, ss.total_earnings, ss.salary_id, p.payroll_id, p.payroll_status AS payroll_stat, CASE WHEN ss.staff IS NULL THEN 'No Salary Structure' WHEN p.staff IS NULL THEN 'Pending' ELSE 'Generated' END AS payroll_status FROM staffs s LEFT JOIN salary_structure ss ON s.staff_id = ss.staff LEFT JOIN payroll p ON s.staff_id = p.staff AND MONTH(p.payroll_date) = MONTH(CURDATE()) AND YEAR(p.payroll_date) = YEAR(CURDATE()) WHERE s.store = :store AND s.staff_status != 2 ORDER BY s.last_name ASC;");
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
        //fetch staffs to generate payroll for selected month
        //fetch staffs to generate payroll for selected month
public function fetch_generate_payrollpermonth($store, $payroll_date){
    $sql = "SELECT 
                s.staff_id, 
                s.last_name, 
                s.other_names, 
                s.staff_number, 
                s.department, 
                s.designation, 
                s.gender, 
                ss.basic_salary, 
                ss.housing_allowance, 
                ss.transport_allowance, 
                ss.utility_allowance, 
                ss.medical_allowance, 
                ss.other_allowance, 
                ss.total_earnings, 
                ss.salary_id, 
                p.payroll_id, 
                p.payroll_status AS payroll_stat,
                CASE 
                    WHEN ss.staff IS NULL THEN 'No Salary Structure'
                    WHEN p.staff IS NULL THEN 'Pending'
                    ELSE 'Generated'
                END AS payroll_status
            FROM staffs s
            LEFT JOIN salary_structure ss 
                ON s.staff_id = ss.staff
            LEFT JOIN payroll p 
                ON s.staff_id = p.staff 
                AND MONTH(p.payroll_date) = MONTH(:payroll_date)
                AND YEAR(p.payroll_date) = YEAR(:payroll_date)
            WHERE s.store = :store
                AND s.staff_status != 2
            ORDER BY s.last_name ASC";

    $stmt = $this->connectdb()->prepare($sql);
    $stmt->bindValue(":store", $store);
    $stmt->bindValue(":payroll_date", $payroll_date);
    $stmt->execute();

    if($stmt->rowCount() > 0){
        return $stmt->fetchAll();
    } else {
        return [];
    }
}

        //fetch staff for attendance check out for the day
        public function fetch_staff_checkout($store){
            $get_user = $this->connectdb()->prepare("SELECT s.staff_id, s.last_name, s.other_names, s.staff_number, s.department, s.designation, s.gender, a.time_in, a.attendance_id FROM staffs s INNER JOIN attendance a ON s.staff_id = a.staff AND DATE(a.attendance_date) = CURDATE()WHERE a.store = :store AND a.attendance_status = 0 AND a.time_out IS NULL ORDER BY a.marked_date ASC;");
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
         //fetch pending investment payments
        public function fetch_pending_investment(){
            $sql = "SELECT i.investment_id, i.currency, i.customer, i.amount, i.total_in_dollar, COALESCE(SUM(p.amount), 0) AS amount_paid FROM investments i LEFT JOIN investment_payments p ON i.investment_id = p.investment GROUP BY i.investment_id, i.currency,i.customer, i.amount, i.total_in_dollar HAVING amount_paid < i.amount ORDER BY i.post_date ASC";

            $get_user = $this->connectdb()->prepare($sql);
            $get_user->execute();

            if($get_user->rowCount() > 0){
                return $get_user->fetchAll();
            }else{
                return "No records found";
            }
        }
         public function fetch_overdue_investment(){
            $sql = "SELECT i.investment_id, i.currency, i.customer, i.amount, i.units, i.total_in_dollar, i.start_date, i.contract_status, i.post_date, COALESCE(SUM(p.amount), 0) AS amount_paid, DATE_ADD(i.start_date, INTERVAL 30 DAY) AS due_date FROM investments i LEFT JOIN investment_payments p ON i.investment_id = p.investment GROUP BY i.investment_id, i.currency, i.customer, i.amount, i.total_in_dollar, i.start_date HAVING amount_paid < i.amount AND CURDATE() > DATE_ADD(i.start_date, INTERVAL 30 DAY) ORDER BY i.post_date ASC";
            $get_user = $this->connectdb()->prepare($sql);
            $get_user->execute();

            if($get_user->rowCount() > 0){
                return $get_user->fetchAll();
            }else{
                return "No records found";
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
        //fetch details count with condition
        public function fetch_count_cond1neg($table, $column, $condition, $negCol, $negVal){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column AND $negCol != :negValue");
            $get_user->bindValue("$column", $condition);
            $get_user->bindValue("negValue", $negVal);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        //fetch tax rate perincome
        public function fetch_tax_rate($income){
            $get_user = $this->connectdb()->prepare("SELECT * FROM tax_rules WHERE :income BETWEEN min_income AND max_income");
            $get_user->bindValue("income", $income);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows =  $get_user->fetchAll();
                return $rows;
            }else{
                $rows =  "No record found";
                return $rows;
            }
        }
        //fetch late days
        public function fetch_late_days($staff){
            $query = "SELECT COUNT(*) AS total_late_days 
                FROM attendance 
                WHERE staff = :staff_id 
                AND TIME(time_in) > '08:05:00' AND time_in IS NOT NULL
                AND MONTH(attendance_date) = MONTH(CURDATE()) 
                AND YEAR(attendance_date) = YEAR(CURDATE())
            ";
            
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result && $result['total_late_days'] > 0){
                return $result['total_late_days'];
            } else {
                return 0;
            }
        }
        //fetch late days for specific month
        public function fetch_late_days_month($staff, $date){
            $query = "SELECT COUNT(*) AS total_late_days 
                FROM attendance 
                WHERE staff = :staff_id 
                AND TIME(time_in) > '08:05:00' AND time_in IS NOT NULL
                AND MONTH(attendance_date) = MONTH(:attend_date) 
                AND YEAR(attendance_date) = YEAR(:attend_date)
            ";
            
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->bindValue(":attend_date", $date);
            $stmt->execute();

            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if($result && $result['total_late_days'] > 0){
                return $result['total_late_days'];
            } else {
                return 0;
            }
        }

        //fetch employee attendance for the current month
        /* public function fetch_staff_work_days($staff){
            $query = "SELECT COUNT(DISTINCT DATE(time_in)) AS attendance_days FROM attendance WHERE staff = :staff_id AND MONTH(attendance_date) = MONTH(CURDATE()) AND YEAR(attendance_date) = YEAR(CURDATE())";
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row && $row['attendance_days'] ? (int)$row['attendance_days'] : 0;
        } */
        // fetch employee attendance for the current month
public function fetch_staff_work_days($staff){
    $query = "
        SELECT COUNT(DISTINCT DATE(attendance_date)) AS attendance_days
        FROM attendance
        WHERE staff = :staff_id
          AND attendance_date >= DATE_FORMAT(CURDATE(), '%Y-%m-01')
          AND attendance_date <  DATE_ADD(DATE_FORMAT(CURDATE(), '%Y-%m-01'), INTERVAL 1 MONTH)
          AND time_in IS NOT NULL
    ";

    $stmt = $this->connectdb()->prepare($query);
    $stmt->bindValue(":staff_id", $staff);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($row['attendance_days'] ?? 0);
}

        /* //fetch employee attendance for a specific month
        public function fetch_staff_work_days_month($staff, $date){
            $query = "SELECT COUNT(DISTINCT DATE(time_in)) AS attendance_days FROM attendance WHERE staff = :staff_id AND MONTH(attendance_date) = MONTH(:attend_date) AND YEAR(attendance_date) = YEAR(:attend_date)";
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->bindValue(":attend_date", $date);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row && $row['attendance_days'] ? (int)$row['attendance_days'] : 0;
        } */
// fetch employee attendance for a specific month
public function fetch_staff_work_days_month($staff, $date){
    $query = "
        SELECT COUNT(DISTINCT DATE(attendance_date)) AS attendance_days
        FROM attendance
        WHERE staff = :staff_id
          AND attendance_date >= DATE_FORMAT(:attend_date, '%Y-%m-01')
          AND attendance_date <  DATE_ADD(DATE_FORMAT(:attend_date, '%Y-%m-01'), INTERVAL 1 MONTH)
          AND time_in IS NOT NULL
    ";

    $stmt = $this->connectdb()->prepare($query);
    $stmt->bindValue(":staff_id", $staff);
    $stmt->bindValue(":attend_date", $date);
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return (int)($row['attendance_days'] ?? 0);
}

        //fetch employee leave days for current month
        /* public function fetch_leave_days($staff){
            $query = "SELECT SUM(DATEDIFF(LEAST(COALESCE(l.returned, l.end_date, CURDATE()), LAST_DAY(CURDATE())), GREATEST(l.start_date, DATE_FORMAT(CURDATE(), '%Y-%m-01'))) + 1) AS leave_days FROM leaves l
                WHERE 
                    l.employee = :staff_id
                    AND (l.leave_status = 1 OR l.leave_status = 2)
                    AND (
                        MONTH(l.start_date) = MONTH(CURDATE())
                        OR MONTH(COALESCE(l.returned, l.end_date)) = MONTH(CURDATE())
                    )
            ";
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row && $row['leave_days'] ? $row['leave_days'] : 0;
        } */
       // fetch employee leave days for current month (Mon–Fri only)
        public function fetch_leave_days($staff){
            $query = "
                SELECT SUM(
                    (
                        DATEDIFF(
                            LEAST(COALESCE(l.returned, l.end_date, CURDATE()), LAST_DAY(CURDATE())),
                            GREATEST(l.start_date, DATE_FORMAT(CURDATE(), '%Y-%m-01'))
                        ) + 1
                    )
                    -
                    (
                        FLOOR(
                            (
                                DATEDIFF(
                                    LEAST(COALESCE(l.returned, l.end_date, CURDATE()), LAST_DAY(CURDATE())),
                                    GREATEST(l.start_date, DATE_FORMAT(CURDATE(), '%Y-%m-01'))
                                )
                                + DAYOFWEEK(GREATEST(l.start_date, DATE_FORMAT(CURDATE(), '%Y-%m-01')))
                            ) / 7
                        ) * 2
                    )
                ) AS leave_days
                FROM leaves l
                WHERE 
                    l.employee = :staff_id
                    AND (l.leave_status = 1 OR l.leave_status = 2)
                    AND (
                        MONTH(l.start_date) = MONTH(CURDATE())
                        OR MONTH(COALESCE(l.returned, l.end_date)) = MONTH(CURDATE())
                    )
            ";

            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row && $row['leave_days'] ? (int)$row['leave_days'] : 0;
        }

        //fetch employee leave days for specific month
       /*  public function fetch_leave_days_month($staff, $date){
            $query = "SELECT SUM(DATEDIFF(LEAST(COALESCE(l.returned, l.end_date, LAST_DAY(:leave_date)), LAST_DAY(:leave_date)), GREATEST(l.start_date, DATE_FORMAT(:leave_date, '%Y-%m-01'))) + 1) AS leave_days FROM leaves l WHERE l.employee = :staff_id AND (l.leave_status = 1 OR l.leave_status = 2) AND (MONTH(l.start_date) = MONTH(:leave_date) OR MONTH(COALESCE(l.returned, l.end_date)) = MONTH(:leave_date))";
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->bindValue(":leave_date", $date);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row && $row['leave_days'] ? (int)$row['leave_days'] : 0;
        } */
            // fetch employee leave days for specific month (Mon–Fri only)
        public function fetch_leave_days_month($staff, $date){
            $query = "
                SELECT SUM(
                    (
                        DATEDIFF(
                            LEAST(COALESCE(l.returned, l.end_date, LAST_DAY(:leave_date)), LAST_DAY(:leave_date)),
                            GREATEST(l.start_date, DATE_FORMAT(:leave_date, '%Y-%m-01'))
                        ) + 1
                    )
                    -
                    (
                        FLOOR(
                            (
                                DATEDIFF(
                                    LEAST(COALESCE(l.returned, l.end_date, LAST_DAY(:leave_date)), LAST_DAY(:leave_date)),
                                    GREATEST(l.start_date, DATE_FORMAT(:leave_date, '%Y-%m-01'))
                                )
                                + DAYOFWEEK(GREATEST(l.start_date, DATE_FORMAT(:leave_date, '%Y-%m-01')))
                            ) / 7
                        ) * 2
                    )
                ) AS leave_days
                FROM leaves l
                WHERE 
                    l.employee = :staff_id
                    AND (l.leave_status = 1 OR l.leave_status = 2)
                    AND (
                        MONTH(l.start_date) = MONTH(:leave_date)
                        OR MONTH(COALESCE(l.returned, l.end_date)) = MONTH(:leave_date)
                    )
            ";

            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->bindValue(":leave_date", $date);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row && $row['leave_days'] ? (int)$row['leave_days'] : 0;
        }

        // Fetch employee suspension days for current month
/*         public function fetch_suspension_days($staff){
            $query = "SELECT 
                    SUM(
                        DATEDIFF(
                            LEAST(
                                COALESCE(s.recall_date, LAST_DAY(CURDATE())),
                                LAST_DAY(CURDATE())
                            ),
                            GREATEST(
                                s.suspension_date,
                                DATE_FORMAT(CURDATE(), '%Y-%m-01')
                            )
                        ) + 1
                    ) AS suspension_days
                FROM suspensions s
                WHERE 
                    s.staff = :staff_id
                    AND (
                        (MONTH(s.suspension_date) = MONTH(CURDATE()) AND YEAR(s.suspension_date) = YEAR(CURDATE()))
                        OR (MONTH(COALESCE(s.recall_date, s.suspension_date)) = MONTH(CURDATE()) AND YEAR(COALESCE(s.recall_date, s.suspension_date)) = YEAR(CURDATE()))
                    )
            ";

            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row && $row['suspension_days'] ? $row['suspension_days'] : 0;
        } */
        // Fetch employee suspension days for current month (Mon–Fri only)
        public function fetch_suspension_days($staff){
            $query = "
                SELECT SUM(
                    (
                        DATEDIFF(
                            LEAST(COALESCE(s.recall_date, LAST_DAY(CURDATE())), LAST_DAY(CURDATE())),
                            GREATEST(s.suspension_date, DATE_FORMAT(CURDATE(), '%Y-%m-01'))
                        ) + 1
                    )
                    -
                    (
                        FLOOR(
                            (
                                DATEDIFF(
                                    LEAST(COALESCE(s.recall_date, LAST_DAY(CURDATE())), LAST_DAY(CURDATE())),
                                    GREATEST(s.suspension_date, DATE_FORMAT(CURDATE(), '%Y-%m-01'))
                                )
                                + DAYOFWEEK(GREATEST(s.suspension_date, DATE_FORMAT(CURDATE(), '%Y-%m-01')))
                            ) / 7
                        ) * 2
                    )
                ) AS suspension_days
                FROM suspensions s
                WHERE 
                    s.staff = :staff_id
                    AND (
                        (MONTH(s.suspension_date) = MONTH(CURDATE()) AND YEAR(s.suspension_date) = YEAR(CURDATE()))
                        OR (MONTH(COALESCE(s.recall_date, s.suspension_date)) = MONTH(CURDATE()) AND YEAR(COALESCE(s.recall_date, s.suspension_date)) = YEAR(CURDATE()))
                    )
            ";

            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row && $row['suspension_days'] ? (int)$row['suspension_days'] : 0;
        }

        // Fetch employee suspension days for specific month
        /* public function fetch_suspension_days_month($staff, $date){
            $query = "SELECT SUM(DATEDIFF(LEAST(COALESCE(s.recall_date, LAST_DAY(:search_date)),  LAST_DAY(:search_date)), GREATEST(s.suspension_date, DATE_FORMAT(:search_date, '%Y-%m-01'))) + 1) AS suspension_days FROM suspensions s WHERE s.staff = :staff_id AND ((MONTH(s.suspension_date) = MONTH(:search_date) AND YEAR(s.suspension_date) = YEAR(:search_date))OR (MONTH(COALESCE(s.recall_date, s.suspension_date)) = MONTH(:search_date) AND YEAR(COALESCE(s.recall_date, s.suspension_date)) = YEAR(:search_date)))";

            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->bindValue(":search_date", $date);
            $stmt->execute();

            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row && $row['suspension_days'] ? $row['suspension_days'] : 0;
        } */

        // Fetch employee suspension days for specific month (Mon–Fri only)
        public function fetch_suspension_days_month($staff, $date){
            $query = "
                SELECT SUM(
                    (
                        DATEDIFF(
                            LEAST(COALESCE(s.recall_date, LAST_DAY(:search_date)), LAST_DAY(:search_date)),
                            GREATEST(s.suspension_date, DATE_FORMAT(:search_date, '%Y-%m-01'))
                        ) + 1
                    )
                    -
                    (
                        FLOOR(
                            (
                                DATEDIFF(
                                    LEAST(COALESCE(s.recall_date, LAST_DAY(:search_date)), LAST_DAY(:search_date)),
                                    GREATEST(s.suspension_date, DATE_FORMAT(:search_date, '%Y-%m-01'))
                                )
                                + DAYOFWEEK(GREATEST(s.suspension_date, DATE_FORMAT(:search_date, '%Y-%m-01')))
                            ) / 7
                        ) * 2
                    )
                ) AS suspension_days
                FROM suspensions s
                WHERE 
                    s.staff = :staff_id
                    AND (
                        (MONTH(s.suspension_date) = MONTH(:search_date) AND YEAR(s.suspension_date) = YEAR(:search_date))
                        OR (MONTH(COALESCE(s.recall_date, s.suspension_date)) = MONTH(:search_date) AND YEAR(COALESCE(s.recall_date, s.suspension_date)) = YEAR(:search_date))
                    )
            ";

            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":staff_id", $staff);
            $stmt->bindValue(":search_date", $date);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            return $row && $row['suspension_days'] ? (int)$row['suspension_days'] : 0;
        }

        // fetch total working days (all calendar days) for current month
        /* public function fetch_total_working_days(){
            $query = "SELECT DAY(LAST_DAY(CURDATE())) AS working_days
            ";
            $stmt = $this->connectdb()->prepare($query);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row ? $row['working_days'] : 0;
        }
        // fetch total working days (all calendar days) for selected month
        public function fetch_total_working_days_month($date){
            $query = "SELECT DAY(LAST_DAY(:search_date)) AS working_days
            ";
            $stmt = $this->connectdb()->prepare($query);
            $stmt->bindValue(":search_date", $date);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $row ? $row['working_days'] : 0;
        } */

        // fetch total working days (Mon-Fri) for current month
public function fetch_total_working_days(){
    $query = "
        SELECT COUNT(*) AS working_days
        FROM (
            SELECT DATE_ADD(DATE_SUB(CURDATE(), INTERVAL DAY(CURDATE())-1 DAY), INTERVAL t.i DAY) AS day
            FROM (
                SELECT 0 AS i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
                UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19
                UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24
                UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29
                UNION ALL SELECT 30
            ) t
        ) d
        WHERE MONTH(d.day) = MONTH(CURDATE()) AND DAYOFWEEK(d.day) NOT IN (1,7)
    ";
    $stmt = $this->connectdb()->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['working_days'] : 0;
}

// fetch total working days (Mon-Fri) for selected month
public function fetch_total_working_days_month($date){
    $query = "
        SELECT COUNT(*) AS working_days
        FROM (
            SELECT DATE_ADD(DATE_SUB(:search_date, INTERVAL DAY(:search_date)-1 DAY), INTERVAL t.i DAY) AS day
            FROM (
                SELECT 0 AS i UNION ALL SELECT 1 UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4
                UNION ALL SELECT 5 UNION ALL SELECT 6 UNION ALL SELECT 7 UNION ALL SELECT 8 UNION ALL SELECT 9
                UNION ALL SELECT 10 UNION ALL SELECT 11 UNION ALL SELECT 12 UNION ALL SELECT 13 UNION ALL SELECT 14
                UNION ALL SELECT 15 UNION ALL SELECT 16 UNION ALL SELECT 17 UNION ALL SELECT 18 UNION ALL SELECT 19
                UNION ALL SELECT 20 UNION ALL SELECT 21 UNION ALL SELECT 22 UNION ALL SELECT 23 UNION ALL SELECT 24
                UNION ALL SELECT 25 UNION ALL SELECT 26 UNION ALL SELECT 27 UNION ALL SELECT 28 UNION ALL SELECT 29
                UNION ALL SELECT 30
            ) t
        ) d
        WHERE MONTH(d.day) = MONTH(:search_date) AND DAYOFWEEK(d.day) NOT IN (1,7)
    ";
    $stmt = $this->connectdb()->prepare($query);
    $stmt->bindValue(":search_date", $date);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    return $row ? $row['working_days'] : 0;
}
        //fetch count 2 condition
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
        //fetch count 2 condition and 1 negative condition
        public function fetch_count_2cond1neg($table, $column1, $condition1, $column2, $condition2,$negCon, $negVal){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column1 = :$column1 AND $column2 = :$column2 AND $negCon != :negVal");
            $get_user->bindValue("$column1", $condition1);
            $get_user->bindValue("$column2", $condition2);
            $get_user->bindValue("negVal", $negVal);
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
        // select count with date and positive condition
        public function fetch_count_curDatePosCon($table, $column, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE date($column) = CURDATE() AND $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        // select count with month, year and positive condition
        public function fetch_count_curMonth($table, $column, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE MONTH($column) = MONTH(CURDATE()) AND YEAR($column) = YEAR(CURDATE()) AND $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        // select count with specific month, year and positive condition
        public function fetch_count_specificMonth($table, $column, $date, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE MONTH($column) = MONTH($date) AND YEAR($column) = YEAR($date) AND $condition = :$condition");
            $get_user->bindValue("$condition", $value);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                return $get_user->rowCount();
            }else{
                return "0";
            }
        }
        // select count with month, year and positive condition and 2 of either condition
        public function fetch_count_curMonth2con($table, $column, $condition, $value, $con2, $val2, $val3){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE MONTH($column) = MONTH(CURDATE()) AND YEAR($column) = YEAR(CURDATE()) AND $condition = :$condition $con2 = :val2 OR $con2 = :val3");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("val2", $val2);
            $get_user->bindValue("val3", $val3);
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
        //fetch active crop cycle for client portal
        public function fetch_active_cycles($customer){
            $get_user = $this->connectdb()->prepare("SELECT fields.field_name, fields.field_status, crop_cycles.cycle_id, crop_cycles.crop, crop_cycles.variety, area_used, crop_cycles.start_date, crop_cycles.expected_harvest, crop_cycles.expected_yield, crop_cycles.created_at FROM fields, crop_cycles WHERE crop_cycles.cycle_status = 0 AND crop_cycles.field = fields.field_id AND fields.customer = :customer ORDER BY created_at");
            $get_user->bindvalue("customer", $customer);
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
        public function fetch_payroll_months($store){
            $get_user = $this->connectdb()->prepare("SELECT MIN(payroll_date) AS payroll_date 
    FROM payroll 
    WHERE store = :store 
    GROUP BY YEAR(payroll_date), MONTH(payroll_date)
    ORDER BY YEAR(payroll_date) DESC, MONTH(payroll_date) DESC");
            $get_user->bindvalue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch salary disbursement
        public function fetch_salary($store){
        $sql = "SELECT YEAR(payroll_date) AS pay_year, MONTH(payroll_date) AS pay_month, DATE_FORMAT(payroll_date, '%M %Y') AS payroll_month, payroll_date, SUM(net_pay) AS total_net_pay, COUNT(DISTINCT staff) AS total_staffs FROM payroll WHERE store = :store AND payroll_status = 1 GROUP BY YEAR(payroll_date), MONTH(payroll_date)ORDER BY YEAR(payroll_date) DESC, MONTH(payroll_date) DESC";
        $stmt = $this->connectdb()->prepare($sql);
        $stmt->bindValue(':store', $store);
        $stmt->execute();
        return $stmt->rowCount() ? $stmt->fetchAll() : "No records found";
    }
       // fetch salary for a specific month
public function fetch_salary_month($store, $paymonth){
    $sql = "SELECT SUM(net_pay) AS total_net_pay
        FROM payroll
        WHERE store = :store
          AND payroll_status = 1
          AND YEAR(payroll_date) = YEAR(:paymonth)
          AND MONTH(payroll_date) = MONTH(:paymonth)
    ";

    $stmt = $this->connectdb()->prepare($sql);
    $stmt->bindValue(':store', $store);
    $stmt->bindValue(':paymonth', $paymonth);
    $stmt->execute();

    return $stmt->fetch(PDO::FETCH_ASSOC)['total_net_pay'] ?? 0;
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
        //fetch between two dates and 2 Condition grouped by 
        public function fetch_details_2date2ConGr($table, $col, $value1, $value2, $con, $con_value, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE  $col BETWEEN '$value1' AND '$value2' AND $con = :$con GROUP BY $group");
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
        //fetch with current month and two condition
        public function fetch_details_curmonth2Con($table, $column, $condition, $value, $con2, $value2){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND $con2 = :$con2 AND MONTH($column) = MONTH(CURDATE()) AND YEAR($column) = YEAR(CURDATE())");
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
        //fetch with current month and one condition
        public function fetch_details_curmonthCon($table, $column, $condition, $value){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $condition =:$condition AND MONTH($column) = MONTH(CURDATE()) AND YEAR($column) = YEAR(CURDATE())");
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
        //fetch monthly payroll
        public function fetch_monthly_payroll($store, $month){
            $get_user = $this->connectdb()->prepare("SELECT * FROM payroll WHERE store = :store AND MONTH(payroll_date) = MONTH(:month) AND YEAR(payroll_date) = YEAR(:month) ORDER BY payroll_date DESC");
            $get_user->bindValue(':store', $store);
            $get_user->bindValue(':month', $month);
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
        //fetch land for assignment
        public function fetch_fields(){
            $get_user = $this->connectdb()->prepare("SELECT fields.field_name, fields.field_size, fields.field_id, fields.location, fields.soil_type, fields.soil_ph, fields.topography FROM fields, assets WHERE fields.customer = 0 AND assets.asset_status = 2 AND fields.asset_id = assets.asset_id ORDER BY fields.field_name");
            // $get_user->bindValue("store", $store);
            $get_user->execute();
            if($get_user->rowCount() > 0){
                $rows = $get_user->fetchAll();
                return $rows;
            }else{
                $rows = "No records found";
                return $rows;
            }
        }
        //fetch land for assignment
        public function fetch_customer_due_fields($customer){
            $get_user = $this->connectdb()->prepare("SELECT fields.field_name, fields.field_size, fields.field_id, assigned_fields.contract_status, assigned_fields.assigned_id, assigned_fields.total_due FROM fields, assigned_fields WHERE fields.customer = :customer AND assigned_fields.contract_status = 1 AND assigned_fields.field = fields.field_id ORDER BY fields.field_name");
            $get_user->bindValue("customer", $customer);
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
        //fetch sum with 3 condition
        public function fetch_sum_tripple($table, $column1, $condition, $value, $condition2, $value2, $con3, $val3){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition = :$condition AND $condition2 = :$condition2 AND $con3 = :con3");
            $get_user->bindValue("$condition", $value);
            $get_user->bindValue("$condition2", $value2);
            $get_user->bindValue("con3", $val3);
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
        //fetch sum with 3 condition with 1 negative
        public function fetch_sum_tripple1Neg($table, $column1, $condition, $value, $condition2, $value2, $condition3, $value3){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) AS total FROM $table WHERE $condition = :$condition AND $condition2 = :$condition2 AND $condition3 != :$condition3");
            $get_user->bindValue("$condition", $value);
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
        public function fetch_sum_date_range($table, $column, $col1, $val1, $date_col, $date_limit){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column) as total FROM $table WHERE $col1 = :col1 AND $date_col <= :date_limit");
            $get_user->bindValue("col1", $val1);
            $get_user->bindValue("date_limit", $date_limit);
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
        //fetch sum between two dates and condition grouped by
        public function fetch_sum_2date2CondGr($table, $column1, $column2, $col3, $condition1, $value1, $value2, $value3, $val4, $group){
            $get_user = $this->connectdb()->prepare("SELECT SUM(amount) AS total FROM (SELECT MAX($column1) AS amount FROM $table WHERE $column2 = :$column2 AND $col3 = :$col3 AND DATE($condition1) BETWEEN '$value1' AND '$value2' GROUP BY $group) AS unique_invoices;");
            $get_user->bindValue("$column2", $value3);
            $get_user->bindValue("$col3", $val4);
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
        public function fetch_trial_balance($store){
            $get_daily = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits, post_date, account, account_type FROM transactions WHERE date(post_date) = CURDATE() AND trx_status = 0 AND store = :store GROUP BY account DESC");
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
        // fetch trial balance by date range
        public function fetch_trial_balanceDate($from, $to, $store){
            $get_daily = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits, post_date, account, account_type FROM transactions WHERE date(post_date) BETWEEN '$from' AND '$to' AND trx_status = 0 AND store = :store GROUP BY account DESC");
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
        //fetch with yearly condition group by
        public function fetch_details_yearlyGroup($table, $condition1, $store, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE YEAR($condition1) = YEAR(CURDATE()) AND store = :store GROUP BY $group");
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
        //fetch with a specific selected year group
        public function fetch_details_specYearGro($table, $full_date, $store, $group){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE YEAR(post_date) = YEAR('$full_date') AND store = :store GROUP BY $group");
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
        //fetch monthly briquette production
        public function fetch_monthly_briquette($store){
            $get_user = $this->connectdb()->prepare(" SELECT COALESCE(SUM(total_leave_cost), 0) +COALESCE(SUM(total_peel_cost), 0) +COALESCE(SUM(total_crown_cost), 0) AS total FROM briquette_production WHERE store = :store AND MONTH(date_produced) = MONTH(CURDATE()) AND YEAR(date_produced) = YEAR(CURDATE())");
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
        public function fetch_monthly_pos($cond, $value, $month, $year, $store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits FROM transactions WHERE MONTH(post_date) = $month AND YEAR(post_date) = $year AND $cond = :$cond AND store = :store");
            $get_user->bindValue("$cond", $value);
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
        //fetch yearly financial position
        public function fetch_yearly_pos($cond, $value, $year, $store){
            $get_user = $this->connectdb()->prepare("SELECT SUM(debit) AS debits, SUM(credit) AS credits FROM transactions WHERE YEAR(post_date) = $year AND $cond = :$cond AND store = :store");
            $get_user->bindValue("$cond", $value);
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

        //fetch sum for a specific month and year and a condition
         public function fetch_sum_monthYear2Cond($table, $column1, $column2, $month, $year, $con, $value, $con2, $val2){
            $get_user = $this->connectdb()->prepare("SELECT SUM($column1) as total FROM $table WHERE MONTH($column2) = $month AND YEAR($column2) = $year AND $con = :$con AND $con2 = :$con2");
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
         public function fetch_monthlyStatement($account, $month, $year, $store){
            $get_user = $this->connectdb()->prepare("SELECT * FROM transactions WHERE MONTH(post_date) = $month AND YEAR(post_date) =  $year AND account = :account AND store = :store /* GROUP BY trx_number */ ORDER BY post_date");
            $get_user->bindValue("account", $account);
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
        //fetch yearly account statement
        public function fetch_yearlyStatement($account, $year, $store){
            $get_user = $this->connectdb()->prepare("SELECT * FROM transactions WHERE YEAR(post_date) =  $year AND account = :account AND store = :store /* GROUP BY trx_number */ ORDER BY post_date");
            $get_user->bindValue("account", $account);
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
        //fetch between two dates and Condition order
        public function fetch_details_2date2ConOrder($table, $column, $col2, $condition1, $value1, $value2, $column_value, $col_val2, $order){
            $get_user = $this->connectdb()->prepare("SELECT * FROM $table WHERE $column = :$column AND $col2 = :$col2 AND $condition1 BETWEEN '$value1' AND '$value2' ORDER BY $order");
            $get_user->bindValue("$column", $column_value);
            $get_user->bindValue("$col2", $col_val2);
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
        public function fetch_payables($store){
            $get_user = $this->connectdb()->prepare("SELECT COALESCE(SUM(debit), 0) - COALESCE(SUM(credit), 0) AS total_due, account FROM transactions WHERE class = 7 AND store = :store GROUP BY account HAVING total_due > 0");
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

