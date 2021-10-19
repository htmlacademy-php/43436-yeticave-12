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
            'name' => $lot['name'],
            'description' => $lot['description'],
            'bitStep' => $lot['rate_step'],
            'startPrice' => $lot['start_price'],
            'imageUrl' => $lot['image_url'],
            'category' => $lot['category_name'],
            'expirationDate' => $lot['expiration_at']
        ];
    }

     // --- fetch all bits for specific LOT ---
    function fetchBits($lotId) {
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

        foreach ($searchResultArray as $item) {
            // add placeholder in the end of every word in the search and save it as string
            $readyStringForSearch = $readyStringForSearch . $item . '* ';
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
