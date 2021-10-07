<?php
    /**
     * show value of the field name
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
    function validateFilled($fieldName) {
        if (empty($_POST[$fieldName])) {
            return "The field can't be empty";
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
        $len = strlen($_POST[$fieldName]);

        if ($len < $minChars or $len > $maxChars) {
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
        if (empty(trim($_POST[$fieldName]))) {
            return "$errorText";
        }
        if (!empty(trim($_POST[$fieldName]))) {
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
    function validateImage($fieldName) {
        if (isset($_FILES[$fieldName])) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $file_name = $_FILES[$fieldName]['tmp_name'];

            $file_type = finfo_file($finfo, $file_name);

            if ($file_type !== 'image/jpg' || $file_type !== 'image/jpeg' || $file_type !== 'image/png') {
                print('Upload a picture in the format png, jpg, jpeg');
            }
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
    function validatePositiveNumber($fieldName, $errorMessage) {
        if (intval($_POST[$fieldName]) <= 0) {
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
        $setDate = strtotime($_POST[$fieldName]);
        $currentDate = date('Y-m-d');
        $currentDateTimestamp = strtotime($currentDate);
        if (empty($_POST[$fieldName])) {
            return "Enter the closing date of the auction";
        }
        if ($setDate <= $currentDateTimestamp) {
            return "The date cannot be from the past or today";
        }
    }


