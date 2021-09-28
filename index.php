<?php
    // include a file with helper functions
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $isAuth = rand(0, 1);

    $userName = 'Katia Sheleh';

    $categories = ['Доски и лыжи', 'Крепления', 'Ботинки', 'Одежда', 'Инструменты', 'Разное'];

    $lots = [
        [
            'name' => '2014 Rossignol District Snowboard',
            'category' => 'Доски и лыжи',
            'price' => '10999.456',
            'imgUrl' => 'lot-1.jpg',
            'expiryDate' => '2021-09-29'
        ],
        [
            'name' => 'DC Ply Mens 2016/2017 Snowboard',
            'category' => 'Доски и лыжи',
            'price' => '159999',
            'imgUrl' => 'lot-2.jpg',
            'expiryDate' => '2021-10-20'
        ],
        [
            'name' => 'Крепления Union Contact Pro 2015 года размер L/XL',
            'category' => 'Крепления',
            'price' => '8000',
            'imgUrl' => 'lot-3.jpg',
            'expiryDate' => '2021-11-01'
        ],
        [
            'name' => 'Ботинки для сноуборда DC Mutiny Charocal',
            'category' => 'Ботинки',
            'price' => '10999',
            'imgUrl' => 'lot-4.jpg',
            'expiryDate' => '2021-10-30'
        ],
        [
            'name' => 'Куртка для сноуборда DC Mutiny Charocal',
            'category' => 'Одежда',
            'price' => '7500.6468',
            'imgUrl' => 'lot-5.jpg',
            'expiryDate' => '2022-12-26'
        ],
        [
            'name' => 'Маска Oakley Canopy',
            'category' => 'Разное',
            'price' => '999.99',
            'imgUrl' => 'lot-6.jpg',
            'expiryDate' => '2022-04-22'
        ]
    ];

    // call data for page content
    $pageContent = include_template('main.php', [
        'categories' => $categories,
        'lots' => $lots
    ]);

    // call data for index.php
    $layout = include_template('layout.php', [
        'title' => 'Main Page',
        'isAuth' => $isAuth,
        'userName' => $userName,
        'categories' => $categories,
        'pageContent' => $pageContent
    ]);

    // show content of the page
    print($layout);
