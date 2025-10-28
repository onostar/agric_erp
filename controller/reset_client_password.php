<?php

    if(isset($_POST['change_password'])){
        $username = strtolower(htmlspecialchars(stripslashes($_POST['username'])));
        $new_password = htmlspecialchars(stripslashes($_POST['new_password']));
        $re_password = htmlspecialchars(stripslashes($_POST['retype_password']));


        // instantiate classes
        include "../classes/dbh.php";
        include "../classes/reset_client_password.php";
        include "../classes/reset_client_passwordContr.php";
        $change_password = new reset_passwordController($username, $new_password, $re_password);

        //execute change password
        $change_password->change_password();
    }