<?php
    // include files
    require_once('helpers/helpers.php');
    require_once('helpers/formatters.php');
    require_once('db-connection.php');

    // setup default timezone
    date_default_timezone_set('Europe/Madrid');

    $isAuth = rand(0, 1);

    $userName = 'Katia Sheleh';



    // --- CATEGORIES ---

    // SQL query: get all categories
    $categoriesSqlQuery = 'SELECT name, technical_name FROM categories';

     // get the categories as array
    $categories = fetchDBData($categoriesSqlQuery);



    // --- LOTS ---

    function fetchLots() {
        // SQL query: get the newest, open lots.
        // Result includes title, starting price, image link, expiration date, category name. show maximum 6 lots
        $lotsSqlQuery = 'SELECT l.name, start_price, image_url, c.name as category_name, l.expiration_at
        FROM lots l
        INNER JOIN categories c ON category_id = c.id
        WHERE expiration_at > NOW()
        ORDER BY created_at DESC
        LIMIT 6';

        // get the categories as array
        $lots = fetchDBData($lotsSqlQuery);

        return array_map(
            static function(array $lot): array {
                return [
                    'name' => $lot['name'],
                    'startPrice' => $lot['start_price'],
                    'imageUrl' => $lot['image_url'],
                    'category' => $lot['category_name'],
                    'expirationDate' => $lot['expiration_at']
                ];
            },
            $lots
        );
    }

    $lots = fetchLots();

    // PAGE STRUCTURE

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
