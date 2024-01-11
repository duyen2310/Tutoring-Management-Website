<?php
class Validate
{
    // functions to validate user login
    public static function isEmail($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }
    public static function isPasswordSafe($password)
    {
        if (strlen($password) < 8) {
            return false;
        }


        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }

        // regex that checks if password contains at least one lowercase letter
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }

        // regex that checks if password contains at least one number
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        // Regex that checks if  password contains at least one special character
        if (!preg_match('/[\'\!^£$%&*()}{@#~?><>,|=_+¬-]/', $password)) {
            return false;
        }


        return true;
    }
    public static function isValidName($name)
    {
        // regex that checks if the name contains at least one alphabetical character
        return preg_match('/[a-zA-Z]/', $name);
    }
    public static function isUsernameValid($username)
    {
        // check if username is more than 2 characters long
        return strlen($username) > 2 && strlen($username) <=50;
    }
    public static function validateUrl($url) {
        // Use FILTER_VALIDATE_URL to check if the URL is valid
        if (filter_var($url, FILTER_VALIDATE_URL)) {
            return true;
        } else {
            return false;
        }
    }
}
