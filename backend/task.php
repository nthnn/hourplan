<?php
    header("Access-Control-Allow-Origin: *");

    include_once("lib/db_config.php");
    include_once("lib/guidv4.php");
    include_once("lib/response.php");
    include_once("lib/session.php");
    include_once("lib/time.php");
    include_once("lib/validator.php");

    class Task {
        public static function create(
            $title, $desc,
            $start_dt, $end_dt,
            $repeat, $color, $ends,
            $type, $session, $categories
        ) {
            if(!validateUnixTimestamp($start_dt) ||
                !validateUnixTimestamp($end_dt)) {
                respondError("Invalid UNIX date/timestamp.");
                return;
            }

            if($repeat < 0 || $repeat > 3) {
                respondError("Invalid repeat type value.");
                return;
            }

            if($ends != 0 && !validateUnixTimestamp($ends)) {
                respondError("Invalid UNIX date/timestamp for end range.");
                return;
            }

            if($type != "0" && $type != "1") {
                respondError("Invalid new task type.");
                return;
            }

            if(!validateHexColor($color)) {
                respondError("Invalid color hexadecimal string value.");
                return;
            }

            if($start_dt > $end_dt) {
                respondError("Start date cannot be greater than end date.");
                return;
            }

            if($start_dt == $end_dt) {
                respondError("Start date cannot be the same with end date.");
                return;
            }

            $sessionId = getSessionUserId($session);
            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            global $db_conn;
            $res = mysqli_query(
                $db_conn,
                "INSERT INTO task ".
                    "(`user_id`, `title`, `desc`, `start`, `end`, `repeat`, `ends`, `type`, `color`, `categories`, `is_finished`) VALUES (".
                    $sessionId.", ".
                    "\"".base64_encode(htmlentities($title))."\", ".
                    "\"".base64_encode(htmlentities($desc))."\", ".
                    $start_dt.", ".
                    $end_dt.", ".
                    $repeat.", ".
                    $ends.", ".
                    $type.", ".
                    "\"".$color."\", ".
                    "\"".base64_encode(htmlentities($categories))."\", 0)"
            );

            if(!$res) {
                respondFailed();
                return;
            }

            respondOk();
        }

        private static function uncheckFinishedStatus($id) {
            global $db_conn;
            mysqli_query(
                $db_conn,
                "UPDATE task SET `is_finished`=0 WHERE id=".$id
            );
        }

        public static function fetchTodaysTasks($session, $type, $isFinished) {
            global $db_conn;

            $sessionId = getSessionUserId($session);
            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            $currentDate = date('Y-m-d');
            $startOfDay = strtotime($currentDate.' - 3 days');
            $endOfDay = strtotime($currentDate.' + 3 days');

            $result = mysqli_query(
                $db_conn,
                ($type != 2 ? "SELECT `id`, `user_id`, `title`, `desc`, `start`, `end`, `repeat`, `color`, `ends`, `type`, `is_finished`, `categories` FROM task ".
                    "WHERE user_id=".$sessionId." ".
                    "AND type=".$type :
                    "SELECT `id`, `user_id`, `title`, `desc`, `start`, `end`, `repeat`, `color`, `ends`, `type`, `is_finished`, `categories` FROM task WHERE user_id=".$sessionId)
            );

            if(!$result) {
                respondFailed();
                return;
            }

            $currentDate = date('Y-m-d');
            $startOfDay = strtotime($currentDate.' - 3 days');
            $endOfDay = strtotime($currentDate.' + 3 days');

            $rows = mysqli_fetch_all($result);
            $tasks = array();

            for($i = 0; $i < count($rows); $i++) {
                $row = $rows[$i];

                if($row[6] == 0 && $row[10] == false && (
                    ($row[4] >= $startOfDay && $row[5] <= $startOfDay) ||
                    ($row[4] >= $endOfDay && $row[5] <= $endOfDay) ||
                    ($startOfDay >= $row[4] && $endOfDay <= $row[4]) ||
                    ($startOfDay >= $row[5] && $endOfDay <= $row[5])))
                    array_push($tasks, $row);
                else if($row[6] == 1) {
                    $row[4] = getTimeForTodayBasedOn($row[4]);
                    $row[5] = getTimeForTodayBasedOn($row[5]);
                    $row[10] = 0;

                    Task::uncheckFinishedStatus($row[0]);
                    array_push($tasks, $row);
                }
            }

            jsonResponse(
                json_encode(array(
                    "status"=> 1,
                    "tasks"=> $tasks
                ))
            );
        }

        public static function highlightableDates($session) {
            global $db_conn;

            $sessionId = getSessionUserId($session);
            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            $result = mysqli_query(
                $db_conn,
                "SELECT start, end FROM task WHERE is_finished=0 AND user_id=".$sessionId
            );

            if(!$result) {
                respondFailed();
                return;
            }

            jsonResponse(
                json_encode(array(
                    "status"=> 1,
                    "dates"=> mysqli_fetch_all($result)
                ))
            );
        }

        public static function markTask($session, $id, $finished) {
            global $db_conn;

            $sessionId = getSessionUserId($session);
            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            if(!validateNumber($id)) {
                respondError("Invalid task ID.");
                return;
            }

            if($finished != "0" && $finished != "1") {
                respondError("Invalid finished status value.");
                return;
            }

            $result = mysqli_query(
                $db_conn,
                "UPDATE task SET is_finished=".$finished." ".
                    "WHERE user_id=".$sessionId." AND id=".$id
            );

            if(!$result) {
                respondFailed();
                return;
            }

            respondOk();
            return;
        }
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(!isset($_POST["action"]) || empty($_POST["action"])) {
            respond403();
            return;
        }

        $action = $_POST["action"];
        if($action == "create" &&
            isset($_POST["session"]) && !empty($_POST["session"]) &&
            isset($_POST["title"]) && !empty($_POST["title"]) &&
            isset($_POST["desc"]) && !empty($_POST["desc"]) &&
            isset($_POST["start_dt"]) && $_POST["start_dt"] != "" &&
            isset($_POST["end_dt"]) && $_POST["end_dt"] != "" &&
            isset($_POST["repeat"]) && $_POST["repeat"] != "" &&
            isset($_POST["color"]) && $_POST["color"] != "" &&
            isset($_POST["ends"]) && $_POST["ends"] != "" &&
            isset($_POST["type"]) && $_POST["type"] != "" &&
            isset($_POST["categories"])) {
            $session = $_POST["session"];
            $title = $_POST["title"];
            $desc = $_POST["desc"];
            $start_dt = $_POST["start_dt"];
            $end_dt = $_POST["end_dt"];
            $repeat = $_POST["repeat"];
            $color = $_POST["color"];
            $ends = $_POST["ends"];
            $type = $_POST["type"];
            $categories = $_POST["categories"];
    
            Task::create(
                $title,
                $desc,
                $start_dt,
                $end_dt,
                $repeat,
                $color,
                $ends,
                $type,
                $session,
                $categories
            );
            return;
        }
        else if($action == "todays_unfinished_tasks" &&
            isset($_POST["session"]) && !empty($_POST["session"])) {
            $session = $_POST["session"];

            Task::fetchTodaysTasks($session, 0, 0);
            return;
        }
        else if($action == "todays_unfinished_schedules" &&
            isset($_POST["session"]) && !empty($_POST["session"])) {
            $session = $_POST["session"];

            Task::fetchTodaysTasks($session, 1, 0);
            return;
        }
        else if($action == "all_task" &&
            isset($_POST["session"]) && !empty($_POST["session"])) {
            $session = $_POST["session"];

            Task::fetchTodaysTasks($session, 2, 0);
            return;
        }
        else if($action == "highlightable_dates" &&
            isset($_POST["session"]) && !empty($_POST["session"])) {
            $session = $_POST["session"];

            Task::highlightableDates($session);
            return;
        }
        else if($action == "mark_task" &&
            isset($_POST["session"]) && !empty($_POST["session"]) &&
            isset($_POST["id"]) && $_POST["id"] != "" &&
            isset($_POST["finished"]) && $_POST["finished"] != "") {
            $hash = $_POST["session"];
            $id = $_POST["id"];
            $finished = $_POST["finished"];

            Task::markTask($hash, $id, $finished);
            return;
        }
    }

    respond403();
?>