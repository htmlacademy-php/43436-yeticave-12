<?php
// define constants
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'auctionshop');

// connect to DB
$dbConnection = mysqli_connect(DB_SERVER , DB_USER, DB_PASSWORD, DB_DATABASE);

// setup encoding format for Unicode characters
mysqli_set_charset($dbConnection, 'utf8');

// error handling
if ($dbConnection === false) {
    die('Connection error: ' . mysqli_connect_error());
}

/**
 * Get data from DB table
 *
 * @param $sqlQuery: string with SQL query
 *
 * @return object mysqli_query() performs a query against a database
 */
function getQueryResult($sqlQuery) {
    // get global variable with db connection
    global $dbConnection;

    // get the result
    $result = mysqli_query($dbConnection, $sqlQuery);

    // error handling
    if ($result === false) {
        die("MySQL Error: " . mysqli_error($dbConnection));
    }

    return $result;
}

/**
 * Fetch all result rows as an associative array, a numeric array, or both
 *
 * @param $sqlQuery: string with SQL query
 *
 * @return array
 */
function fetchDBData($sqlQuery) {
    $result = getQueryResult($sqlQuery);

    // get the result as array
    return mysqli_fetch_all($result, MYSQLI_ASSOC);
};


// !!! TEMPORAL will be removed (during next task) !!!
$catQuery1 = 'UPDATE categories SET technical_name = "boards" WHERE technical_name = "category_1"';
$catQuery2 = 'UPDATE categories SET technical_name = "attachment" WHERE technical_name = "category_2"';
$catQuery3 = 'UPDATE categories SET technical_name = "boots" WHERE technical_name = "category_3"';
$catQuery4 = 'UPDATE categories SET technical_name = "clothing" WHERE technical_name = "category_4"';
$catQuery5 = 'UPDATE categories SET technical_name = "tools" WHERE technical_name = "category_5"';
$catQuery6 = 'UPDATE categories SET technical_name = "other" WHERE technical_name = "category_6"';

getQueryResult($catQuery1);
getQueryResult($catQuery2);
getQueryResult($catQuery3);
getQueryResult($catQuery4);
getQueryResult($catQuery5);
getQueryResult($catQuery6);
