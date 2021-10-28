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
 * Execute sql query
 *
 * @param $sqlQuery: string with SQL query
 *
 * @return object mysqli_query() performs a query against a database
 */
function executeQuery($sqlQuery) {
    // get global variable with db connection
    global $dbConnection;

    // execute sql
    return mysqli_query($dbConnection, $sqlQuery);
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
