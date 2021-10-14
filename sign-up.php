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

    $requiredFields = ['email', 'password', 'name', 'message'];

    $errors = [];

    $rules = [
        'email' => function () {
            return validateEmail($_POST['email']);
        },
        'password' => function () {
            return validateText($_POST['password'], 5, 15, 'Write your password');
        },
        'name' => function () {
            return validateText($_POST['name'], 5, 25, 'Write your name.');
        },
        'message' => function () {
            return validateText($_POST['message'], 10, 100, 'Write down how to contact you');
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
        $password = trim($_POST['password']);
        $name = trim($_POST['name']);
        $description = trim($_POST['message']);


        createNewUser($email, $password, $name, $description);

        // empty errors
        $errors = [];

        // TODO remove 43436-yeticave-12 directory
        // redirect to a page with the lot information
        header("Location:/43436-yeticave-12/pages/login.html");

    }

    // PAGE STRUCTURE

    // call data for a categories navigation list
    $categoriesList = include_template('categories-nav-list.php', [
        'categories' => $categories
    ]);

    // call data for page content
    $pageContent = include_template('sign-up-template.php', [
        'categories' => $categories,
        'errors' => $errors,
        'categoriesList' => $categoriesList
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Registration',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'pageContent' => $pageContent,
        'categoriesList' => $categoriesList
    ]);

    // show content of the page
    print($layout);
