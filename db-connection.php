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
if (!$dbConnection) {
    print('Connection error: ' . mysqli_connect_error());
    die();
}
