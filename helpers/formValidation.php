<?php
    /**
     * show value of the field name ONLY for POST method
     *
     * @param $name: field name
     *
     * @return string field value
     */
    function getPostVal($fieldName) {
        return $_POST[$fieldName] ?? "";
    }


    /**
     * Validate email field
     *
     * @param $fieldName: field name
     *
     * @return string field value
     */
    function validateEmail($fieldName) {
        if (!filter_input(INPUT_POST, $fieldName, FILTER_VALIDATE_EMAIL)) {
            return "Введите корректный email";
        }
    }

    /**
     * Validate if a field is empty
     *
     * @param $fieldName: field name
     *
     * @return string field value
     */
    function validateFilled($fieldName, $errorMessage = "The field can't be empty") {
        if (empty($fieldName)) {
            return $errorMessage;
        }
    }

    /**
     * Validate minimum and maximum number of characters allowed
     *
     * @param $fieldName: field name
     * @param $minChars : minimum number of characters allowed
     * @param $maxChars : maximum number of characters allowed
     *
     * @return string field value
     */
    function isCorrectLength($fieldName, $minChars, $maxChars) {
        $len = strlen($fieldName);

        if ($len < $minChars || $len > $maxChars) {
            return "The value must be between $minChars and $maxChars characters";
        }
    }

    /**
     * Validate text field
     *
     * @param $fieldName : field name
     * @param $minChars : minimum number of characters allowed
     * @param $maxChars : maximum number of characters allowed
     * @param $errorText : warning text
     *
     * @return string warning text or nothing
     */
    function validateText($fieldName, $minChars, $maxChars, $errorText) {
        $text = trim($fieldName);
        if (empty($text)) {
            return "$errorText";
        }
        if (!empty($text)) {
            isCorrectLength($fieldName, $minChars, $maxChars);
        }
    }

    /**
     * Validate an image
     *
     * @param $fieldName: field name
     *
     * @return string warning text or nothing
     */
   function validateImage($fieldName)
{
    $errorMessage = 'Upload a picture in the format png, jpg, jpeg';

    // show warning if there are no image uploaded
    if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['size'] === 0) {
        return $errorMessage;
    }

    $mType = mime_content_type($_FILES[$fieldName]['tmp_name']); // В tmp_name лежит прямой путь до файла (временное местоположение)

    if (!in_array($mType, ['image/jpg', 'image/jpeg', 'image/png'], true)) {
        return $errorMessage;
    }

    return null;
}

    /**
     * Validate positive number
     *
     * @param $fieldName: field name
     * @param $errorMessage warning text
     *
     * @return string warning text or nothing
     */
    function validateNotNegativeNumber($fieldName, $errorMessage) {
        if (intval($fieldName) <= 0) {
            return "$errorMessage";
        }
    }



    /**
     * Validate positive number
     *
     * @param $fieldName: field name
     * @param $errorMessage warning text
     *
     * @return string warning text or nothing
     */
    function validateDate($fieldName) {
        // convert date to Timestamp
        $setDate = strtotime($fieldName);
        $currentDate = date('Y-m-d');
        $currentDateTimestamp = strtotime($currentDate);
        if (empty($fieldName)) {
            return "Enter the closing date of the auction";
        }
        if ($setDate <= $currentDateTimestamp) {
            return "The date cannot be from the past or today";
        }
    }


