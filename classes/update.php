<?php 
date_default_timezone_set("Africa/Lagos");
    class Update_table extends Dbh{
        public function update($table, $column, $condition, $value, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column = :$column WHERE $condition = :$condition");
            $update->bindValue("$column", $value);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
        }
        //update any table with a condition
       public function updateAny($table, $data, $con, $val) {
            // Build SET part of the query
            $setPart = implode(", ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($data)));

            // Prepare the query
            $sql = "UPDATE $table SET $setPart WHERE $con = :where_$con";
            $update = $this->connectdb()->prepare($sql);

            // Bind SET values
            foreach($data as $column => $value) {
                $update->bindValue(":$column", $value);
            }

            // Bind WHERE condition
            $update->bindValue(":where_$con", $val);

            // Execute
            return $update->execute();
        }
        //update single with 2 condition
        public function update2cond($table, $column, $condition, $condition2, $value, $condition_value, $condition_value2){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column = :$column WHERE $condition = :$condition AND $condition2 = :$condition2");
            $update->bindValue("$column", $value);
            $update->bindValue("$condition", $condition_value);
            $update->bindValue("$condition2", $condition_value2);
            $update->execute();
        }
        //update single with 2 condition 1 negative
        public function update2cond1neg($table, $column, $condition, $condition2, $value, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column = :$column WHERE $condition = :$condition AND $condition2 != 0.000");
            $update->bindValue("$column", $value);
            $update->bindValue("$condition", $condition_value);
            // $update->bindValue("$condition2", $condition_value2);
            $update->execute();
        }
        //update with date
        public function checkOut($column1, $column2, $condition, $value1, $value2, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE check_ins SET $column1 = :$column1, $column2 = :$column2, checked_out = CURDATE() WHERE $condition = :$condition");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
            /* if($update){
                echo "<div class='info'><p>Updated successfully! <i class='fas fa-check'></i></p></div>";
            }else{
                echo "<div class='info'><p class='exist'>Update failed! <i class='fas fa-ban'></i></p></div>";
            } */
        }
        //update double
        public function update_double($table, $column1, $value1, $column2, $value2, $condition, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2 WHERE $condition = :$condition");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
            /* if($update){
                echo "<div class='info'><p>Updated successfully! <i class='fas fa-check'></i></p></div>";
            }else{
                echo "<div class='info'><p class='exist'>Update failed! <i class='fas fa-ban'></i></p></div>";
            } */
        }
        //update tripple
        public function update_tripple($table, $column1, $value1, $column2, $value2, $column3, $value3, $condition, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2, $column3 = :$column3 WHERE $condition = :$condition");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$column3", $value3);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
        }
        //update tripple with 2 condition
        public function update_tripple2Cond($table, $column1, $value1, $column2, $value2, $column3, $value3, $condition1, $condition_value1, $condition2, $condition_value2){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2, $column3 = :$column3 WHERE $condition1 = :$condition1 AND $condition2 =:$condition2");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$column3", $value3);
            $update->bindValue("$condition1", $condition_value1);
            $update->bindValue("$condition2", $condition_value2);
            $update->execute();
        }
        //update double with 2 condition
        public function update_double2Cond($table, $column1, $value1, $column2, $value2, $condition1, $condition_value1, $condition2, $condition_value2){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2 WHERE $condition1 = :$condition1 AND $condition2 =:$condition2");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            // $update->bindValue("$column3", $value3);
            $update->bindValue("$condition1", $condition_value1);
            $update->bindValue("$condition2", $condition_value2);
            $update->execute();
        }
        //update quadruple
        public function update_quadruple($table, $column1, $value1, $column2, $value2, $column3, $value3, $column4, $value4, $condition, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2, $column3 = :$column3, $column4 = :$column4 WHERE $condition = :$condition");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$column3", $value3);
            $update->bindValue("$column4", $value4);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
        }
        //update multiple
        public function update_multiple($table, $column1, $value1, $column2, $value2, $column3, $value3, $column4, $value4, $column5, $value5, $condition, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2, $column3 = :$column3, $column4 = :$column4, $column5 = :$column5 WHERE $condition = :$condition");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$column3", $value3);
            $update->bindValue("$column4", $value4);
            $update->bindValue("$column5", $value5);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
        }
        //update six
        public function update_six($table, $column1, $value1, $column2, $value2, $column3, $value3, $column4, $value4, $column5, $value5, $column6, $value6, $condition, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE $table SET $column1 = :$column1, $column2 = :$column2, $column3 = :$column3, $column4 = :$column4, $column5 = :$column5, $column6 = :$column6 WHERE $condition = :$condition");
            $update->bindValue("$column1", $value1);
            $update->bindValue("$column2", $value2);
            $update->bindValue("$column3", $value3);
            $update->bindValue("$column4", $value4);
            $update->bindValue("$column5", $value5);
            $update->bindValue("$column6", $value6);
            $update->bindValue("$condition", $condition_value);
            $update->execute();
        }
        //update quantity (addition)
        public function update_quantity($value1, $value2, $value3, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE items SET cost_price = :cost_price, sales_price = :sales_price, quantity = quantity + :quantity WHERE item_id = :item_id");
            $update->bindValue("cost_price", $value1);
            $update->bindValue("sales_price", $value2);
            $update->bindValue("quantity", $value3);
            $update->bindValue("item_id", $condition_value);
            $update->execute();
            /* if($update){
                echo "<div class='info'><p>Updated successfully! <i class='fas fa-check'></i></p></div>";
            }else{
                echo "<div class='info'><p class='exist'>Update failed! <i class='fas fa-ban'></i></p></div>";
            } */
        }
        //update quantity (subtraction) for stockin
        public function subtract_quantity($value, $condition_value, $store){
            $update = $this->connectdb()->prepare("UPDATE inventory SET quantity = quantity - :quantity WHERE item = :item AND store = :store");
            $update->bindValue("quantity", $value);
            $update->bindValue("store", $store);
            $update->bindValue("item", $condition_value);
            $update->execute();
            /* if($update){
                echo "<div class='info'><p>Updated successfully! <i class='fas fa-check'></i></p></div>";
            }else{
                echo "<div class='info'><p class='exist'>Update failed! <i class='fas fa-ban'></i></p></div>";
            } */
        }
        //update quantity (addition) for sales
        public function increase_qty($value, $condition_value){
            
            $update = $this->connectdb()->prepare("UPDATE sales SET quantity = quantity + :quantity WHERE sales_id = :sales_id");
            $update->bindValue("quantity", $value);
            $update->bindValue("sales_id", $condition_value);
            $update->execute();
            /* if($update){
                echo "<div class='info'><p>Updated successfully! <i class='fas fa-check'></i></p></div>";
            }else{
                echo "<div class='info'><p class='exist'>Update failed! <i class='fas fa-ban'></i></p></div>";
            } */
        }
        //update quantity (addition) for sales
        public function decrease_qty($value, $condition_value){
            $update = $this->connectdb()->prepare("UPDATE sales SET quantity = quantity - :quantity WHERE sales_id = :sales_id");
            $update->bindValue("quantity", $value);
            $update->bindValue("sales_id", $condition_value);
            $update->execute();
        }
        //update quantity (substraction) for inventory after sales
        public function update_inv_qty($value, $condition_value, $store){
            $update = $this->connectdb()->prepare("UPDATE inventory SET quantity = quantity - :quantity WHERE item = :item AND store = :store");
            $update->bindValue("quantity", $value);
            $update->bindValue("item", $condition_value);
            $update->bindValue("store", $store);
            $update->execute();
        }

        //update password
        public function updatePassword($username, $current_password, $new_password){
            $get_pwd = $this->connectdb()->prepare("SELECT user_password FROM users WHERE username = :username");
            $get_pwd->bindValue("username",$username);
            $get_pwd->execute();
            if($get_pwd->rowCount() > 0){
                //verify password
                $user_password = $get_pwd->fetch();
                $confirm_password = password_verify($current_password, $user_password->user_password);
                if($confirm_password == false){
                    echo "<p class='exist'>Current password is wrong!</p>";
                }else{
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_password = $this->connectdb()->prepare("UPDATE users SET user_password = :user_password WHERE username = :username");
                    $update_password->bindValue("user_password", $hashed_password);
                    $update_password->bindValue("username", $username);
                    $update_password->execute();

                    if($update_password){
                        echo "<p>Password updated Successfuly</p>";
                        session_start();
                        session_unset();
                        session_destroy();
                        
                    }else{
                        echo "<p class'exist'>Failed to update password</p>";
                    }
                }
                
            }else{
                echo "<p class='exist'>Password doesn't match</p>";
            }
        } 
        //update client password
        public function updateClientPassword($username, $current_password, $new_password){
            $get_pwd = $this->connectdb()->prepare("SELECT user_password FROM customers WHERE customer_email = :customer_email");
            $get_pwd->bindValue("customer_email",$username);
            $get_pwd->execute();
            if($get_pwd->rowCount() > 0){
                //verify password
                $user_password = $get_pwd->fetch();
                $confirm_password = password_verify($current_password, $user_password->user_password);
                if($confirm_password == false){
                    echo "<p class='exist'>Current password is wrong!</p>";
                }else{
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $update_password = $this->connectdb()->prepare("UPDATE users SET user_password = :user_password WHERE customer_email = :customer_email");
                    $update_password->bindValue("user_password", $hashed_password);
                    $update_password->bindValue("customer_email", $username);
                    $update_password->execute();

                    if($update_password){
                        echo "<p>Password updated Successfuly</p>";
                        session_start();
                        session_unset();
                        session_destroy();
                        
                    }else{
                        echo "<p class'exist'>Failed to update password</p>";
                    }
                }
                
            }else{
                echo "<p class='exist'>Password doesn't match</p>";
            }
        } 
        //merge customer
        public function mergeCustomer($table, $correct, $wrong){
            $merge = $this->connectdb()->prepare("UPDATE $table SET customer = $correct WHERE customer = $wrong");
            /* $merge->bindValue("customer", $correct);
            $merge->bindValue("customer", $wrong); */
            $merge->execute();
        }
    }

    //controller for update
    /* class update_controller extends Update_table{
        private $table;
        private $column;
        private $condition;
        private $condition_value;
        private $value;

        public function __construct($table, $column, $condition, $condition_value, $value){
            $this->table = $table;
            $this->column = $column;
            $this->condition = $condition;
            $this->condition_value = $condition_value;
            $this->value = $value;
        }

        public function update_table(){
            $this->update($this->table, $this->column, $this->condition, $this->value, $this->condition_value);
            
        }
    } */