<?php
    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');
    require_once('helpers/initSession.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $categories = fetchCategories(); // src => helpers/fetchers.php
    $lots = fetchLots(); // src => helpers/fetchers.php


    // PAGE STRUCTURE

    // call data for page content
    $pageContent = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Main Page',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent,
        'categoriesList' => $categoriesList
    ]);

    // show content of the page
    print($layout);
