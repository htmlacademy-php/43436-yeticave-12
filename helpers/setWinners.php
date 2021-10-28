<?php

    function getLotsWithoutWinner() {
        // SQL query: get all categories
        $lotsWithoutWinnerQuery = "SELECT id, expiration_at, winner_id FROM lots WHERE winner_id IS NULL AND expiration_at <= NOW()";

        // fetch result
        $lots = fetchDBData($lotsWithoutWinnerQuery);

        return array_map(
            static function(array $lot): array {
                return [
                    'id' => $lot['id'],
                    'expirationDate' => $lot['expiration_at'],
                    'winnerId' => $lot['winner_id']
                ];
            },
            $lots
        );
    }


    function getMaxBetValueForLot($lotId) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get all categories
        $sqlQuery = "SELECT is_winner, user_id, MAX(price) AS max_price FROM rates WHERE lot_id = '" . mysqli_real_escape_string($dbConnection, $lotId) . "' GROUP BY user_id";

        // fetch result
        $maxBets = fetchDBData($sqlQuery);

        //get data for single lot
        $bet = $maxBets[0];

        return [
            'authorId' => $bet['user_id'],
            'maxPrice' => $bet['max_price'],
            'isWinner' => $bet['is_winner'],
        ];
    }


    function setLotWinner ($lotId, $winnerId, $maxPrice) {
        // get global variable with db connection
        global $dbConnection;

        // SQL query: get all categories
        $sqlQuery = "UPDATE lots SET winner_id = '". mysqli_real_escape_string($dbConnection, $winnerId) ."' WHERE id = '" . mysqli_real_escape_string($dbConnection, $lotId) . "'";
        $betSqlQuery = "UPDATE rates SET is_winner = '1' WHERE price = '" . mysqli_real_escape_string($dbConnection, $maxPrice) . "' AND user_id = '" . mysqli_real_escape_string($dbConnection, $winnerId) . "'";

        // fetch result
        fetchDBData($betSqlQuery);
        fetchDBData($sqlQuery);
    }

    $lotsWithoutWinner = getLotsWithoutWinner();

    foreach ($lotsWithoutWinner as $lot) {
        $lotId = $lot['id'];
        $bet = getMaxBetValueForLot($lotId);

        if (!is_null($bet['authorId'])) {
            setLotWinner($lotId, $bet['authorId'], $bet['maxPrice']);
        }
    }

