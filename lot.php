<?php
    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');
    require_once('helpers/initSession.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $lotId = $_GET['id'];

    // show 404 error if id is empty
    if (!isset($lotId)) {
        show404();
    }

    $lot = fetchLot($lotId); // src => helpers/fetchers.php

    // show 404 error if lot doesn't exist
    if (empty($lot) === true) {
        show404();
    }

    $bits = fetchBits($lotId); // src => helpers/fetchers.php
    $categories = fetchCategories(); // src => helpers/fetchers.php


    // PAGE STRUCTURE

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for page content
    $pageContent = include_template('lot-template.php', [
        'lot' => $lot,
        'bits' => $bits,
        'categoriesList' => $categoriesList,
        'isAuth' => $isAuth,
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => $lot ? $lot['name'] : 'Not found',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent,
        'categoriesList' => $categoriesList
    ]);

    // show content of the page
    print($layout);
