<?php
/**
 * Format price
 *
 * Examples:
 * formatPrice(98.628); // 99 €
 * formatPrice(1000); // 1 000 €
 * formatPrice(14000.789); // 14 001 €

 * @param string $price number
 *
 * @return string Value with formatted price
 */
function formatPrice($price) {
    $currency = html_entity_decode('&euro;');

    // show an input value if $price isn't a numeric type
    if (!is_numeric($price)) {
        return $price . ' ' . $currency;
    }

    //round the value to a whole number
    $price = ceil($price);

    // add white space as a thousand separator for values >= 1000
    if ($price >= 1000) {
        return number_format($price, 0, '', ' ') . ' ' . $currency;
    }

    // don't format a value for $price < 1000
    return $price . ' ' . $currency;
}


/**
 * Calculate remaining time
 *
 * @param $expiryDate: date in formate YYYY-MM-DD
 *
 * @return array in format [09, 29], where first value is hours and the second one is minutes
 */
function calculateRemainingTime($dateTo) {

    // convert dates to timestamp
    $nowTimestamp = strtotime('now');
    $expirationTimestamp = strtotime($dateTo);

    // subtract one number from another
    $remainingTimestamp = $expirationTimestamp - $nowTimestamp;

    // Divide the difference by the number of seconds in a day
    // 1 hour => 3600
    // 1 minute => 60
    $remainingHours = floor($remainingTimestamp / 3600);
    $remainingMinutes = floor(($remainingTimestamp % 3600) / 60);

    return [
        'hours' => $remainingHours,
        'minutes' => $remainingMinutes
    ];
}
