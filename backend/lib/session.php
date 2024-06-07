<?php

include_once("db_config.php");

function getSessionUserId($hash) {
    global $db_conn;
    $res = mysqli_query(
        $db_conn,
        "SELECT user_id FROM session WHERE hash=\"".$hash."\""
    );

    if($res && mysqli_num_rows($res) != 0) {
        $row = mysqli_fetch_row($res);
        return $row[0];
    }

    return -1;
}

?>