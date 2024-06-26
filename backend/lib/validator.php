<?php

function validateHexColor($color) {
    return preg_match(
        "/^#?([a-fA-F0-9]{3}|[a-fA-F0-9]{6})$/",
        $color
    );
}

function validateUsername($username) {
    return strlen($username) > 6 &&
        preg_match("/^[a-zA-Z0-9_]+$/", $username);
}

function validateEmail($email) {
    if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        return false;

    list($username, $domain) = explode("@", $email);
    if(empty($domain) || strpos($domain, ".") === false)
        return false;

    return checkdnsrr($domain, "MX");
}

function validatePassword($password) {
    return strlen($password) === 32 &&
        preg_match("/^[a-f0-9]+$/", $password);
}

function validateUnixTimestamp($timestamp) {
    if(is_numeric($timestamp) && (int)$timestamp == $timestamp) {
        $timestamp = (int)$timestamp;
        return $timestamp >= -2147483648 && $timestamp <= 2147483647;
    }

    return false;
}

function validateUuid($uuid) {
    return preg_match(
        "/^[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[1-5][0-9a-fA-F]{3}-[89abAB][0-9a-fA-F]{3}-[0-9a-fA-F]{12}$/",
        $uuid
    ) === 1;
}

function validateNumber($number) {
    return filter_var(
        trim($number),
        FILTER_VALIDATE_INT
    ) !== false;
}

?>