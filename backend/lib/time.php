<?php

function getTimeForTodayBasedOn($timestamp) {
    return strtotime(date("Y-m-d").' '.date("H:i:s", $timestamp));
}

?>