<?php
/**
 * Created by PhpStorm
 * User: codetwista
 * Date: 2/6/2023
 * Time: 8:47 PM
 */

/**
 * Display form validation error
 *
 * @param $validation
 * @param $field
 *
 * @return mixed
 */
if (! function_exists('displayError')) {
    function displayError($errors, $field)
    {
        return isset($errors[$field]) ? $errors[$field] : '' ;
    }
}