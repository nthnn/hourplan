<?php
    header("Access-Control-Allow-Origin: *");

    include_once("lib/db_config.php");
    include_once("lib/guidv4.php");
    include_once("lib/response.php");
    include_once("lib/session.php");
    include_once("lib/validator.php");

    class Task {
        public static function create(
            $title, $desc,
            $start_dt, $end_dt,
            $repeat, $color, $ends,
            $type, $session
        ) {
            if(!validateUnixTimestamp($start_dt) ||
                !validateUnixTimestamp($end_dt)) {
                respondError("Invalid UNIX date/timestamp.");
                return;
            }

            if($repeat < 0 || $repeat > 4) {
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

            $sessionId = getSessionUserId($session);
            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            global $db_conn;
            $res = mysqli_query(
                $db_conn,
                "INSERT INTO task ".
                    "(`user_id`, `title`, `desc`, `start`, `end`, `repeat`, `ends`, `type`, `color`, `is_finished`) VALUES (".
                    $sessionId.", ".
                    "\"".$title."\", ".
                    "\"".$desc."\", ".
                    $start_dt.", ".
                    $end_dt.", ".
                    $repeat.", ".
                    $ends.", ".
                    $type.", ".
                    "\"".$color."\", 0)"
            );

            if(!$res) {
                respondFailed();
                return;
            }

            respondOk();
        }

        public static function fetchTodaysTasks($session, $type, $isFinished) {
            global $db_conn;

            $sessionId = getSessionUserId($session);
            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            $startOfDay = strtotime('today midnight');
            $endOfDay = strtotime('tomorrow midnight') - 1;

            $result = mysqli_query(
                $db_conn,
                "SELECT * FROM task ".
                    "WHERE ((".$startOfDay." BETWEEN start AND end) OR".
                    "   (".$endOfDay." BETWEEN start AND end) OR".
                    "   (start BETWEEN ".$startOfDay." AND ".$endOfDay.") OR".
                    "   (end BETWEEN ".$startOfDay." AND ".$endOfDay."))".
                    "AND type=".$type." ".
                    "AND user_id=".$sessionId." ".
                    "AND is_finished=".$isFinished
            );

            if(!$result) {
                respondFailed();
                return;
            }

            jsonResponse(
                json_encode(array(
                    "status"=> 1,
                    "tasks"=> mysqli_fetch_all($result)
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
            isset($_POST["type"]) && $_POST["type"] != "") {
            $session = $_POST["session"];
            $title = $_POST["title"];
            $desc = $_POST["desc"];
            $start_dt = $_POST["start_dt"];
            $end_dt = $_POST["end_dt"];
            $repeat = $_POST["repeat"];
            $color = $_POST["color"];
            $ends = $_POST["ends"];
            $type = $_POST["type"];
    
            Task::create(
                $title,
                $desc,
                $start_dt,
                $end_dt,
                $repeat,
                $color,
                $ends,
                $type,
                $session
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
        else if($action == "highlightable_dates" &&
            isset($_POST["session"]) && !empty($_POST["session"])) {
            $session = $_POST["session"];

            Task::highlightableDates($session);
            return;
        }
    }

    respond403();
?>