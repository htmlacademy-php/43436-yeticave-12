<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');
    require_once('helpers/formValidation.php');
    require_once('helpers/initSession.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $categories = fetchCategories(); // src => helpers/fetchers.php

    $searchResult = [];
    $searchString = '';

    // actions after form submitting
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($_GET['search'])) {

        $searchString = trim($_GET['search']);
        $preparedStringForSearch = prepareStringForSearch($searchString);

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $lotsPerPage = 3;

        $lotsResultTotal = countSearchResult($preparedStringForSearch);
        $pagesTotal = ceil($lotsResultTotal / $lotsPerPage);

        $offset = ($currentPage - 1) * $lotsPerPage;
        $pages = range(1, $pagesTotal);

        $searchResult = fetchSearchedLots($preparedStringForSearch, $lotsPerPage, $offset);
    }

    // PAGE STRUCTURE

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for page content
    $pageContent = include_template('search-template.php', [
        'categoriesList' => $categoriesList,
        'searchString' => $searchString,
        'searchResult' => $searchResult,
        'currentPage' => $currentPage,
        'pages' => $pages,
        'pagesTotal' => $pagesTotal
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Search lot',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent,
        'categoriesList' => $categoriesList
    ]);

    // show content of the page
    print($layout);
