<?php

function respond403() {
    header("HTTP/1.0 403 Forbidden");
}

function jsonResponse($content) {
    header("Content-Type: application/json; charset=utf-8");
    echo $content;
}

function respondOk() {
    jsonResponse("{\"status\": \"1\"}");
}

function respondFailed() {
    jsonResponse("{\"status\": \"0\"}");
}

function respondError($message) {
    jsonResponse("{\"status\": \"0\", \"error\": \"".$message."\"}");
}

?>