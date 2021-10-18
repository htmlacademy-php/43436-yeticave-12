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

    $requiredFields = ['email', 'password'];

    $errors = [];

    $rules = [
        'email' => function () {
            return verifyEmail($_POST['email']);
        },
        'password' => function () {
            return verifyPassword($_POST['password'], $_POST['email']);
        },
    ];

    // check errors in all fields ($_POST)
    foreach ($_POST as $key => $value) {
        if (isset($rules[$key])) {
            $rule = $rules[$key];
            $errors[$key] = $rule();
        }
    }

    // clean entries with value NULL
    $errors = array_filter($errors);

    // actions after form submitting
    if($_SERVER['REQUEST_METHOD'] === 'POST' && count($errors) === 0) {
        $email = trim($_POST['email']);

        // setup session
        $_SESSION['userEmail'] = $email;

        // TODO remove 43436-yeticave-12 directory
        // redirect to the main page
        header("Location:/43436-yeticave-12/index.php");

    }

    // PAGE STRUCTURE

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for page content
    $pageContent = include_template('login-template.php', [
        'categories' => $categories,
        'errors' => $errors,
        'categoriesList' => $categoriesList
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Login',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent,
        'categoriesList' => $categoriesList
    ]);

    // show content of the page
    print($layout);
