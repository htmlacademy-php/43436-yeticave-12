<?php
    // connect to DB
    require_once('db-connection.php');

    // --- fetch CATEGORIES ---
    function fetchCategories() {
        // SQL query: get all categories
        $categoriesSqlQuery = 'SELECT id, name, technical_name FROM categories';

        // get the categories as array
        $categories = fetchDBData($categoriesSqlQuery);

        return array_map(
            static function(array $category): array {
                return [
                    'id' => $category['id'],
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
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get lot information
        $lotSqlQuery = "SELECT
            l.id as lot_id,
            l.author_id,
            l.name,
            l.description,
            l.rate_step,
            l.start_price,
            l.last_price,
            l.image_url,
            c.name as category_name,
            l.expiration_at
            FROM lots l
            INNER JOIN categories c ON category_id = c.id
            WHERE l.id = ?"; // '?' is a parameter for mysqli_stmt_bind_param

        // Prepares an SQL statement for execution
        $stmt = mysqli_prepare($dbConnection, $lotSqlQuery);
        // Binds variables to a prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 's', $id);
        // Executes a prepared statement
        mysqli_stmt_execute($stmt);
        // Gets a result set from a prepared statement as a mysqli_result object
        $result = mysqli_stmt_get_result($stmt);
        // get the result as array
        $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

        if (count($lots) === 0) {
            return [];
        }

        // get data for single lot
        $lot = $lots[0];

        return [
            'id' => $lot['lot_id'],
            'name' => $lot['name'],
            'description' => $lot['description'],
            'betStep' => $lot['rate_step'],
            'startPrice' => $lot['start_price'],
            'lastPrice' => $lot['last_price'],
            'imageUrl' => $lot['image_url'],
            'category' => $lot['category_name'],
            'expirationDate' => $lot['expiration_at'],
            'authorId' => $lot['author_id']
        ];
    }

     // --- fetch all bits for specific LOT ---
    function fetchBets($lotId) {
        // get global variable with db connection
        global $dbConnection;

        $query = "SELECT
            created_at,
            price,
            u.name as user_name
            FROM rates r
            INNER JOIN users u ON r.user_id = u.id
            WHERE r.lot_id = ?"; // '?' is a parameter for mysqli_stmt_bind_param

        // Prepares an SQL statement for execution
        $stmt = mysqli_prepare($dbConnection, $query);
        // Binds variables to a prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 's', $lotId);
        // Executes a prepared statement
        mysqli_stmt_execute($stmt);
        // Gets a result set from a prepared statement as a mysqli_result object
        $result = mysqli_stmt_get_result($stmt);
        // get the result as array
        $bits = mysqli_fetch_all($result, MYSQLI_ASSOC);

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

    function createNewLot($name, $description, $rateStep, $startPrice, $imageUrl, $expirationDate, $categoryId, $userId) {
        // get global variable with db connection
        global $dbConnection;

        $sqlQuery = "INSERT INTO lots
        (created_at, name, description, rate_step, start_price, image_url, expiration_at, category_id, author_id)
        VALUES (NOW(), ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepares an SQL statement for execution
        $stmt = mysqli_prepare($dbConnection, $sqlQuery);
        // Binds variables to a prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 'ssddssii', $name, $description, $rateStep, $startPrice, $imageUrl, $expirationDate, $categoryId, $userId);
        // Executes a prepared statement
        mysqli_stmt_execute($stmt);
    }

    function createNewUser($email, $password, $name, $description) {
        // get global variable with db connection
        global $dbConnection;

        $sqlQuery = "INSERT INTO users
        (registered_at, email, password, name, phone)
        VALUES (NOW(), ?, ?, ?, ?)";

        // hash the password
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        // Prepares an SQL statement for execution
        $stmt = mysqli_prepare($dbConnection, $sqlQuery);
        // Binds variables to a prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 'ssss', $email, $passwordHash, $name, $description);
        // Executes a prepared statement
        mysqli_stmt_execute($stmt);
    }


    /**
     * Fetch single user information by Email
     *
     * @param $email: user email
     *
     * @return array
     */
    function fetchSingleUser($email) {
        // get global variable with db connection
        global $dbConnection;

        $sqlQuery = "SELECT id, email, password, name FROM users WHERE email ='" . mysqli_real_escape_string($dbConnection, $email) . "';";

         // get the result as array
        $resultFetched = fetchDBData($sqlQuery);

        if(count($resultFetched) > 0) {

            // get data for single user
            $resultFetched = $resultFetched[0];

            return [
                'id' => $resultFetched['id'],
                'email' => $resultFetched['email'],
                'password' => $resultFetched['password'],
                'name' => $resultFetched['name'],
            ];
        }

        // return empty array if the user doesn't exist
        return [];

    }


    /**
     * Prepare string for search
     *
     * @param $searchString: init string
     *
     * @return string
     */
    function prepareStringForSearch($searchString) {
        // convert string to array
        $searchResultArray = explode(' ', $searchString);
        $readyStringForSearch = '';

        foreach ($searchResultArray as $searchItem) {
            // check if $item is not empty space
            if(strlen($searchItem) > 0) {
                // add placeholder in the end of every word in the search and save it as string
                $readyStringForSearch = trim($readyStringForSearch) . trim($searchItem) . '* ';
            }
        }
        return trim($readyStringForSearch);
    }


    /**
     * Get total number of found lots
     *
     * @param $searchString: init string
     *
     * @return int : count of found lots
     */
    function countSearchResult($searchString) {
        // get global variable with db connection
        global $dbConnection;

        $sql_lotsTotal = "SELECT COUNT(*) as lotsTotal FROM lots l WHERE expiration_at > NOW() AND MATCH(l.name, l.description) AGAINST('" . mysqli_real_escape_string(
            $dbConnection,
            $searchString
        ) .
            "' IN BOOLEAN MODE);";
        $sql_lots_count_query = mysqli_query($dbConnection, $sql_lotsTotal);

        $lotsTotal = mysqli_fetch_assoc($sql_lots_count_query)['lotsTotal'];

        return $lotsTotal;
    }


    /**
     * Get lots with limit per page
     *
     * @param $searchString: init string
     *
     * @return array : found lots
     */
    function fetchSearchedLots($searchString, $lotsPerPage, $offset) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get the newest, open lots.
        // Result includes title, starting price, image link, expiration date, category name. show maximum 6 lots
        $lotsSqlQuery = "SELECT l.id, l.name, start_price, image_url, c.name as category_name, l.expiration_at
        FROM lots l
        INNER JOIN categories c ON category_id = c.id
        WHERE expiration_at > NOW() AND MATCH(l.name, l.description) AGAINST('" . mysqli_real_escape_string(
        $dbConnection,
        $searchString
        ) .
        "' IN BOOLEAN MODE) ORDER BY id DESC LIMIT " . $lotsPerPage . " OFFSET " . $offset . ";";

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


    /**
     * Get total number of found lots
     *
     * @param $category: category technical name
     *
     * @return int : count of found lots
     */
    function countLotsInCategory($category) {
        // get global variable with db connection
        global $dbConnection;

        $sql_lotsTotal = "SELECT COUNT(*) as lotsTotal
        FROM lots l
        INNER JOIN categories c ON category_id = c.id
        WHERE technical_name = '" . mysqli_real_escape_string($dbConnection, $category) . "' AND expiration_at > NOW()";

        $sql_lots_count_query = mysqli_query($dbConnection, $sql_lotsTotal);

        $lotsTotal = mysqli_fetch_assoc($sql_lots_count_query)['lotsTotal'];

        return $lotsTotal;
    }


    // --- fetch all lots for specific category ---
    function fetchLotsInCategory($category, $lotsPerPage, $offset) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get the newest, open lots.
        // Result includes title, starting price, image link, expiration date, category name. show maximum 6 lots
        $lotsSqlQuery = "SELECT l.id, l.name, start_price, image_url, c.name as category_name, l.expiration_at
        FROM lots l
        INNER JOIN categories c ON category_id = c.id
        WHERE expiration_at > NOW() AND technical_name = '" . mysqli_real_escape_string($dbConnection, $category) . "'
        ORDER BY created_at DESC LIMIT " . $lotsPerPage . " OFFSET " . $offset . ";";

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


    // --- fetch Category ---
    function fetchCategory($category) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get all categories
        $categoriesSqlQuery = "SELECT id, name, technical_name
        FROM categories
        WHERE technical_name = '" . mysqli_real_escape_string($dbConnection, $category) . "'";

        // get the categories as array
        $categories = fetchDBData($categoriesSqlQuery);

        if (count($categories) === 0) {
            return [];
        }

        // get data for single category
        $category = $categories[0];

        return [
            'id' => $category['id'],
            'name' => $category['name'],
            'techName' => $category['technical_name']
        ];
    }


    function createNewBet($userId, $lotId, $betValue) {
        // get global variable with db connection
        global $dbConnection;

        $sqlQuery = "INSERT INTO rates (created_at, user_id, lot_id, price) VALUES (NOW(), ?, ?, ?)";

        // Prepares an SQL statement for execution
        $stmt = mysqli_prepare($dbConnection, $sqlQuery);
        // Binds variables to a prepared statement as parameters
        mysqli_stmt_bind_param($stmt, 'iid', $userId, $lotId, $betValue);
        // Executes a prepared statement
        mysqli_stmt_execute($stmt);
    }


    // --- fetch LOTS ---
    function fetchMyBets($authorId) {
        // get global variable with db connection
        global $dbConnection;
        // SQL query: get the newest, open lots.
        // Result includes title, starting price, image link, expiration date, category name. show maximum 6 lots
        $lotsSqlQuery = "SELECT
            l.id as lot_id,
            l.image_url,
            l.name as lot_name,
            c.name as category_name,
            l.expiration_at,
            r.price as bet_value,
            r.id as bet_id,
            r.created_at as bet_created,
            r.is_winner,
            u.phone
            FROM lots l
            INNER JOIN categories c ON category_id = c.id
            INNER JOIN rates r ON r.lot_id = l.id
            INNER JOIN users u ON r.user_id = u.id
            WHERE r.user_id = '" . mysqli_real_escape_string($dbConnection, $authorId) . "'
            ORDER BY l.created_at DESC";

        // get the categories as array
        $bets = fetchDBData($lotsSqlQuery);

        return array_map(
            static function(array $bet): array {
                return [
                    'id' => $bet['bet_id'],
                    'lotId' => $bet['lot_id'],
                    'name' => $bet['lot_name'],
                    'betValue' => $bet['bet_value'],
                    'betCreated' => $bet['bet_created'],
                    'imageUrl' => $bet['image_url'],
                    'category' => $bet['category_name'],
                    'expirationDate' => $bet['expiration_at'],
                    'isWinner' => $bet['is_winner'],
                    'winnerContact' => $bet['phone']
                ];
            },
            $bets
        );
    }


    function updateLotLastPrice($lotId, $newPrice) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get all categories
        $sqlQuery = "UPDATE lots
            SET last_price = '". mysqli_real_escape_string($dbConnection, $newPrice) ."'
            WHERE id = '" . mysqli_real_escape_string($dbConnection, $lotId) . "'";

        executeQuery($sqlQuery);
    }


    function getLastBet($lotId) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get all categories
        $sqlQuery = "SELECT user_id, price FROM rates WHERE lot_id = '" . mysqli_real_escape_string($dbConnection, $lotId) . "' ORDER BY created_at DESC LIMIT 1";

        // fetch result
        $maxBets = fetchDBData($sqlQuery);

        if (count($maxBets) === 0) {
            return [];
        }

        //get data for single lot
        $bet = $maxBets[0];

        return [
            'authorId' => $bet['user_id'],
            'maxPrice' => $bet['price']
        ];
    }
