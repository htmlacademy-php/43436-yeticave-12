<?php
    // include files
    require_once('db-connection.php');
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $isAuth = rand(0, 1);

    $userName = 'Katia Sheleh';

    $categories = fetchCategories();
    $lots = fetchLots();


    // PAGE STRUCTURE

    // call data for page content
    $pageContent = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Main Page',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
        'pageContent' => $pageContent
    ]);

    // show content of the page
    print($layout);
