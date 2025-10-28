<?php

    session_start();
    session_destroy();
    session_unset();
    header("Location: ../client_portal/index.php");