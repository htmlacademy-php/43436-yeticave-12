<?php
    $isAuth = false;
    $userName = '';

    session_start();

    if (isset($_SESSION['userEmail'])) {
        $isAuth = true;
        // get current username
        $currentUserEmail = $_SESSION['userEmail'];

        // get user name
        $userName = fetchSingleUser($currentUserEmail)['name'];

        // get userId
        $userId = fetchSingleUser($currentUserEmail)['id'];
    }
