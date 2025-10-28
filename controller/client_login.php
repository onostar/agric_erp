<?php

    if(isset($_POST['submit_login'])){
        $username = strtolower(htmlspecialchars(stripslashes($_POST['username'])));
        $password = htmlspecialchars(stripslashes($_POST['password']));


        /* instantiate classes */
        include "../classes/dbh.php";
        include "../classes/client_login.php";
        include "../classes/loginContr.php";

        $loginUser = new LoginController($username, $password);
        /* check and login user */
        $loginUser->get_user();
    }