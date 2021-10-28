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
     * @return string||null error message or null
     */
    function validateEmail($fieldName) {

        // The FILTER_SANITIZE_EMAIL filter removes all illegal characters from an email address.
        $emailSanitized = filter_var($fieldName, FILTER_SANITIZE_EMAIL);

        // check if the field is empty
        if (empty($emailSanitized)) {
            return "Write your Email";
        }

        // The filter_var() function filters a variable with the specified filter.
        // The FILTER_VALIDATE_EMAIL filter validates an e-mail address.
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            return 'Wrong email format';
        }

        //show the error message of the array is not null === email exists
        if ([] !== fetchSingleUser($emailSanitized)) {
            return 'Email already exists';
        }

        // return null if the field doesn't have errors
        return null;
    }


    /**
     * verify email
     *
     * @param $fieldName: field name
     *
     * @return string||null error message or null
     */
    function verifyEmail($fieldName) {
        // The FILTER_SANITIZE_EMAIL filter removes all illegal characters from an email address.
        $emailSanitized = filter_var($fieldName, FILTER_SANITIZE_EMAIL);

        // show error message if the field is empty
        if (empty($emailSanitized)) {
            return "Enter your E-mail address";
        }

        // The filter_var() function filters a variable with the specified filter.
        // The FILTER_VALIDATE_EMAIL filter validates an e-mail address.
        if (!filter_var($emailSanitized, FILTER_VALIDATE_EMAIL)) {
            return 'Wrong email format';
        }

        //show the error message if the email doesn't exist
        if ([] === fetchSingleUser($emailSanitized)) {
            return 'The user is not found';
        }

        return null;
    }


    /**
     * verify password
     *
     * @param $password: password field
     * @param $email: user email field
     *
     * @return string||null error message or null
     */
    function verifyPassword($password, $email) {
        $userDBPassword = '';

        // show error message if the field is empty
        if (empty($password)) {
            return "Password can't be empty";
        }

        // if user doesn't exists
        if ([] === fetchSingleUser($email)) {
            return "User does not exist.";
        }

        // if user exists
        // get user password
        $userDBPassword = fetchSingleUser($email)['password'];

        // compare entered password with the user password (from DB)
        if (!password_verify($password, $userDBPassword)) {
            return "Wrong password";
        }

        return null;
    }


    /**
     * Validate if a field is empty
     *
     * @param $fieldName: field name
     *
     * @return string||null error message or null
     */
    function validateFilled($fieldName, $errorMessage = "The field can't be empty") {
        if (empty($fieldName)) {
            return $errorMessage;
        }
        // return null if the field doesn't have errors
        return null;
    }


    /**
     * Validate text field
     *
     * @param $fieldName : field name
     * @param $minChars : minimum number of characters allowed
     * @param $maxChars : maximum number of characters allowed
     * @param $errorText : warning text
     *
     * @return string||null error message or null
     */
    function validateText($fieldName, $minChars, $maxChars, $errorText) {

        $fieldValueLength = strlen($fieldName);
        $text = trim($fieldName);

        // id the value is empty
        if (empty($text)) {
            return $errorText;
        }

        // check value length and show the error message is it necessary
        if ($fieldValueLength < $minChars || $fieldValueLength > $maxChars) {
            return "The value must be between $minChars and $maxChars characters";
        }
        // return null if the field doesn't have errors
        return null;
    }


    /**
     * Validate an image
     *
     * @param $fieldName: field name
     *
     * @return string||null error message or null
     */
   function validateImage($fieldName) {
        $errorMessage = 'Upload a picture in the format png, jpg, jpeg';

        // show warning if there are no image uploaded
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['size'] === 0) {
            return $errorMessage;
        }

        $mType = mime_content_type($_FILES[$fieldName]['tmp_name']); // In tmp_name there is a direct path to the file (temporary location)

        if (!in_array($mType, ['image/jpg', 'image/jpeg', 'image/png'], true)) {
            return $errorMessage;
        }
        // return null if the field doesn't have errors
        return null;
    }

    /**
     * Validate positive number
     *
     * @param $fieldName: field name
     * @param $errorMessage warning text
     *
     * @return string||null error message or null
     */
    function validateNotNegativeNumber($fieldName, $errorMessage) {
        if (intval($fieldName) <= 0) {
            return "$errorMessage";
        }
        // return null if the field doesn't have errors
        return null;
    }



    /**
     * Validate positive number
     *
     * @param $fieldName: field name
     * @param $errorMessage warning text
     *
     * @return string||null error message or null
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
        // return null if the field doesn't have errors
        return null;
    }

    /**
     * Validate bet value
     *
     * @param $fieldName: field name
     * @param $minBitValue: value of min bet for the lots
     *
     * @return string||null error message or null
     */
    function validateBetValue($fieldName, $minBetValue) {
        if (empty($fieldName)) {
            return 'Enter the bet value';
        }

        if(is_numeric($fieldName) === false) {
            return 'Only numerical values allowed';
        }

        if ($fieldName < $minBetValue) {
            return 'Min. bet must be ' . formatPrice($minBetValue);
        }

    }


