<?php
    // connect to DB
    require_once('db-connection.php');

    // --- fetch CATEGORIES ---
    function fetchCategories() {
        // SQL query: get all categories
        $categoriesSqlQuery = 'SELECT name, technical_name FROM categories';

        // get the categories as array
        $categories = fetchDBData($categoriesSqlQuery);

        return array_map(
            static function(array $category): array {
                return [
                    'name' => $category['name'],
                    'techName' => $category['technical_name']
                ];
            },
            $categories
        );
    }

    // --- fetch LOTS ---
    function fetchLots() {
        // SQL query: get the newest, open lots.
        // Result includes title, starting price, image link, expiration date, category name. show maximum 6 lots
        $lotsSqlQuery = 'SELECT l.id, l.name, start_price, image_url, c.name as category_name, l.expiration_at
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
                    'id' => $lot['id'],
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

    // --- fetch a specific LOT ---
    function fetchLot($id) {

        // SQL query: get lot information
        $lotsSqlQuery = "SELECT l.id, l.name, l.description, l.rate_step, start_price, image_url, c.name as category_name, l.expiration_at
            FROM lots l
            INNER JOIN categories c ON category_id = c.id
            WHERE l.id = '. $id .' ";

        // get the categories as array
        $lots = fetchDBData($lotsSqlQuery);


        return array_map(
            static function(array $lot): array {
                return [
                    'id' => $lot['id'],
                    'name' => $lot['name'],
                    'description' => $lot['description'],
                    'rateStep' => $lot['rate_step'],
                    'startPrice' => $lot['start_price'],
                    'imageUrl' => $lot['image_url'],
                    'category' => $lot['category_name'],
                    'expirationDate' => $lot['expiration_at']
                ];
            },
            $lots
        );
    }
