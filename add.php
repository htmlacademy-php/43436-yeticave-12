<?php
    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('helpers/fetchers.php');
    require_once('helpers/formValidation.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $isAuth = rand(0, 1);

    $userName = 'Katia Sheleh';

    $categories = fetchCategories(); // src => helpers/fetchers.php

    $requiredFields = ['lot-name', 'category', 'message', 'lot-img', 'lot-rate', 'lot-step', 'lot-date'];
    $errors = [];
    $rules = [
        'lot-name' => function () {
            return validateText($_POST['lot-name'], 10, 70, 'Enter the lot name');
        },
        'category' => function () {
            return validateFilled($_POST['category'], 'Select a category');
        },
        'message' => function () {
            return validateText($_POST['message'], 20, 300, 'Enter the lot description');
        },
        'lot-img' => function () {
            return validateImage('lot-img');
        },
        'lot-rate' => function () {
            return validateNotNegativeNumber($_POST['lot-rate'], 'Enter the starting price');
        },
        'lot-step' => function () {
            return validateNotNegativeNumber($_POST['lot-step'], 'Enter the rate step');
        },
        'lot-date' => function () {
            return validateDate($_POST['lot-date']);
        }
    ];


    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    if($_SERVER['REQUEST_METHOD'] === 'POST' && count($errors) === 0) {
        $name = trim($_POST['lot-name']);
        $description = trim($_POST['message']);
        $rateStep = $_POST['lot-step'];
        $startPrice = $_POST['lot-rate'];
        $imageUrl = $_FILES['lot-img']['name'];
        $expirationDate = $_POST['lot-date'];
        $categoryId = $_POST['category'];

        createNewLot($name, $description, $rateStep, $startPrice, $imageUrl, $expirationDate, $categoryId, '1');

        // empty errors
        $errors = [];

        // move the image to the folder "uploads"
        if (isset($_FILES['lot-img'])) {
            $filePath = __DIR__ . '/uploads/';
            $uploadFile = $filePath . $_FILES['lot-img']['name'];
            move_uploaded_file($_FILES['lot-img']['tmp_name'], $uploadFile);
        }

    }

    // PAGE STRUCTURE

    // call data for page content
    $pageContent = include_template('add-lot.php', [
        'categories' => $categories,
        'errors' => $errors
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Add lot',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
        'pageContent' => $pageContent
    ]);

    // show content of the page
    print($layout);
