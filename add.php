<?php
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
            return validateText('lot-name', 10, 70, 'Enter the lot name');
        },
        'category' => function () {
            return validateFilled('category');
        },
        'message' => function () {
            return validateText('message', 20, 300, 'Enter the lot description');
        },
        'lot-img' => function () {
            return validateImage('lot-img');
        },
        'lot-rate' => function () {
            return validatePositiveNumber('lot-rate', 'Enter the starting price');
        },
        'lot-step' => function () {
            return validatePositiveNumber('lot-step', 'Enter the rate step');
        },
        'lot-date' => function () {
            return validateDate('lot-date');
        }
    ];

    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    $errors = array_filter($errors);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        if(count($errors) === 0) {
            $name = $_POST['lot-name'];
            $description = $_POST['message'];
            $rateStep = $_POST['lot-step'];
            $startPrice = $_POST['lot-rate'];
            $imageUrl = $_POST['lot-img'] ?? "";
            $expirationDate = $_POST['lot-date'];
            $categoryId = $_POST['category'];

            $sqlQuery = "INSERT INTO lots
            (created_at, name, description, rate_step, start_price, image_url, expiration_at, category_id, author_id)
            VALUES (NOW(), '$name', '$description', '$rateStep', '$startPrice', '$imageUrl', '$expirationDate', '$categoryId', '2')";

            getQueryResult($sqlQuery);
            // empty errors
            $errors = [];

            // move the image to uploads folder
            if (isset($_FILES['lot-img'])) {
                $fileName = $_FILES['lot-img'];
                $filePath = __DIR__ . '/uploads/';
                $fileUrl = '/uploads/' . $fileName;
                move_uploaded_file($_FILES['lot-img'], $filePath . $fileName);
            }
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
