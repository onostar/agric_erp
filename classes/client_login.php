<?php
    session_start();
    class Login extends Dbh{
        /* check if user exists */
        protected function checkUser($username, $password){
            $get_pwd = $this->connectdb()->prepare("SELECT user_password FROM customers WHERE customer_email = :customer_email AND customer_type = 'Landowner'");
            $get_pwd->bindValue("customer_email",$username);
            $get_pwd->execute();

            if($get_pwd->rowCount() > 0){
                $user_password = $get_pwd->fetch();
                if($user_password->user_password == "123"){
                    $_SESSION['user'] = $username;
                    header("Location: ../client_portal/change_client_password.php");
                }else{
                    $hashedPwd = $user_password->user_password;
                    $check_pwd = password_verify($password, $hashedPwd);

                    if($check_pwd == false){
                        $_SESSION['error'] = "Error! Wrong Password";
                        header("Location: ../client_portal/index.php");
                    }else{
                        $get_user = $this->connectdb()->prepare("SELECT * FROM customers WHERE customer_email = :customer_email AND user_password = :user_password AND customer_type = 'Landowner'");
                        $get_user->bindValue("customer_email", $username);
                        $get_user->bindValue("user_password", $hashedPwd);
                        $get_user->execute();

                        if($get_user->rowCount() > 0){
                            $rows = $get_user->fetchAll();
                            foreach($rows as $row){
                                $user = $row->customer_id;
                            }
                            include "update.php";
                           /*  //update online status
                            $update_online = new Update_table();
                            $update_online->update('users', 'online','user_id', 1, $user); */
                            $_SESSION['user'] = $username;
                            header("Location: ../client_portal/users.php");

                        }else{
                            $_SESSION['error'] = "Invalid Client type";
                            header("Location: ../client_portal/index.php");
                        }
                    }
                }
            }else{
                $_SESSION['error'] = "Error! Invalid username or password";
                header("Location: ../client_portal/index.php");
            }
        }

    }