<?php
    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');
    require_once('helpers/formValidation.php');
    require_once('helpers/initSession.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $lotId = $_GET['id'];

    // show 404 error if id is empty
    if (!isset($lotId)) {
        header('HTTP/1.0 404 Not Found');
        require_once('./404.php');
        exit();
    }

    $lot = fetchLot($lotId); // src => helpers/fetchers.php

    // show 404 error if lot doesn't exist
    if (empty($lot) === true) {
        header('HTTP/1.0 404 Not Found');
        require_once('./404.php');
        exit();
    }

    $bets = fetchBets($lotId); // src => helpers/fetchers.php
    $categories = fetchCategories(); // src => helpers/fetchers.php

    $errors = [];
    $requiredFields = ['betStep'];

    $rules = [
        'betStep' => function ($lot) {
            return validateBetValue($_POST['betStep'], $lot['betStep']);
        },
    ];

    // check errors in all fields ($_POST)
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule($lot);
        }
    }

    // clean entries with value NULL
    $errors = array_filter($errors);

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && count($errors) === 0) {
        $betValue = $_POST['betStep'];
        $currentPrice = $lot['startPrice'];
        $newPrice = $currentPrice + $betValue;

        createNewBet($userId, $lotId, $newPrice);

        updateLotPrice($lotId, $newPrice);

        // TODO remove 43436-yeticave-12 directory
        // redirect to a page with the lot information
        header("Location:/43436-yeticave-12/lot.php?&id=$lotId");

    }

    $lastBet = getLastBet($lotId);
    $betMadeByCurrentUser = (intval($lastBet['authorId']) === intval($userId));

    // PAGE STRUCTURE

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for page content
    $pageContent = include_template('lot-template.php', [
        'lot' => $lot,
        'bets' => $bets,
        'categoriesList' => $categoriesList,
        'isAuth' => $isAuth,
        'errors' => $errors,
        'userId' => $userId,
        'betMadeByCurrentUser' => $betMadeByCurrentUser
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
