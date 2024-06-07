<?php

date_default_timezone_set("Asia/Manila");

function getTimeForTodayBasedOn($timestamp) {
    return strtotime(date("Y-m-d").' '.date("H:i:s", $timestamp));
}

function getDateTimestampBasedOn($timestamp) {
    return strtotime(
        sprintf("%04d-%02d-%02d %02d:%02d:%02d", 
            date("Y"),
            date("m"),
            date("d", $timestamp),
            date("H", $timestamp),
            date("i", $timestamp),
            date("s", $timestamp))
    );
}

?>