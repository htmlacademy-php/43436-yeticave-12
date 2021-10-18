<?php
    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');
    require_once('helpers/initSession.php');

    // empty session data
    $_SESSION = [];

    // TODO remove 43436-yeticave-12 directory
    // redirect to the main page
    header("Location:/43436-yeticave-12/index.php");
