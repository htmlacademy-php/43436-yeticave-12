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
        $lotSqlQuery = "SELECT
            l.id,
            l.name,
            l.description,
            l.rate_step,
            l.start_price,
            l.image_url,
            c.name as category_name,
            l.expiration_at
            FROM lots l
            INNER JOIN categories c ON category_id = c.id
            WHERE l.id = '$id'";

        // get the categories as array
        $result = getQueryResult($lotSqlQuery);

        return mysqli_fetch_object($result);
    }

     // --- fetch all bits for specific LOT ---
    function fetchBits($lotId) {
        $query = "SELECT
            created_at,
            price,
            u.name as user_name
            FROM rates r
            INNER JOIN users u ON r.user_id = u.id
            WHERE r.lot_id = '$lotId'";

        // get the bits as array
        $bits = fetchDBData($query);

        return array_map(
            static function(array $bit): array {
                return [
                    'bitCreated' => $bit['created_at'],
                    'userName' => $bit['user_name'],
                    'bitPrice' => $bit['price']
                ];
            },
            $bits
        );
    }


    // TEMPORAL: WILL BE DELETED
    // UNCOMMENT THE STATEMENTS BELOW TO ADD A NEW COLUMN AND VALUES.

    // $temp = "ALTER TABLE users ADD name char(255)";
    // getQueryResult($temp);
    // $update1 = "UPDATE users SET name = 'Katia Sheleh' WHERE id='1'";
    // getQueryResult($update1);
    // $update2 = "UPDATE users SET name = 'Kate Sh' WHERE id=2";
    // getQueryResult($update2);

