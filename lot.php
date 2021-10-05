<?php
    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $isAuth = rand(0, 1);

    $userName = 'Katia Sheleh';

    $lotId = intval($_GET['id']);

    // show 404 error if we don't have id or is not a number
    if (!isset($_GET['id']) || is_numeric($_GET['id']) === false) {
        show404();
    }

    // show error if there is no lot with the given ID
    $isLotIdExists = fetchDBData('SELECT id FROM lots WHERE id = ' . $lotId);
    if (count($isLotIdExists) === 0) {
        show404();
    }


    $categories = fetchCategories(); // src => helpers/fetchers.php
    $lots = fetchLot($lotId); // src => helpers/fetchers.php


    // PAGE STRUCTURE

    // call data for page content
    $pageContent = include_template('lot-template.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => $lots ? $lots[0]['name'] : 'Not found',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
        'pageContent' => $pageContent
    ]);

    // show content of the page
    print($layout);
