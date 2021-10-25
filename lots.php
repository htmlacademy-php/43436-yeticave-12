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


    // get data for current category
    if ($_SERVER['REQUEST_METHOD'] === 'GET' ) {
        $currentCatTechName = isset($_GET['category']) ? $_GET['category'] : '';
        $currentCategoryData = fetchCategory($currentCatTechName);

        $currentPage = isset($_GET['page']) ? $_GET['page'] : 1;
        $lotsPerPage = 9;

        $lotsTotal = countLotsInCategory($currentCatTechName);

        $pagesTotal = ceil($lotsTotal / $lotsPerPage);

        $offset = ($currentPage - 1) * $lotsPerPage;
        $pages = range(1, $pagesTotal);

        $lotsInCategory = fetchLotsInCategory($currentCatTechName, $lotsPerPage, $offset);
    }

    // PAGE STRUCTURE

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for page content
    $pageContent = include_template('lots-template.php', [
        'categoriesList' => $categoriesList,
        'lots' => $lotsInCategory,
        'currentCategoryData' => $currentCategoryData,
        'currentPage' => $currentPage,
        'pages' => $pages,
        'pagesTotal' => $pagesTotal
    ]);

    // call data for page
    $layout = include_template('layout.php', [
        'title' => $currentCategoryData ? $currentCategoryData['name'] : 'Category',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent,
        'categoriesList' => $categoriesList
    ]);

    // show content of the page
    print($layout);
