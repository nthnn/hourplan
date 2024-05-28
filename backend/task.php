<?php
    header("Access-Control-Allow-Origin: *");

    include_once("lib/db_config.php");
    include_once("lib/guidv4.php");
    include_once("lib/response.php");
    include_once("lib/validator.php");

    class Task {
        public static function create(
            $title, $desc,
            $start_dt, $end_dt,
            $repeat, $ends,
            $type
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

            if(!validateUnixTimestamp($ends)) {
                respondError("Invalid UNIX date/timestamp for end range.");
                return;
            }

            if($type != "0" && $type != "1") {
                respondError("Invalid new task type.");
                return;
            }

            global $db_conn;
            $res = mysqli_query(
                $db_conn,
                "INSERT INTO task VALUES (".
                    "\"".$title."\", ".
                    "\"".$desc."\", ".
                    $start_dt.", ".
                    $end_dt.", ".
                    $repeat.", ".
                    "\"".$ends."\", ".
                    $type.")"
            );

            if(!$res) {
                respondFailed();
                return;
            }

            respondOk();
        }
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(!isset($_POST["action"]) || empty($_POST["action"])) {
            respond403();
            return;
        }

        $action = $_POST["action"];
        if($action == "create" &&
            isset($_POST["title"]) && !empty($_POST["title"]) &&
            isset($_POST["desc"]) && !empty($_POST["desc"]) &&
            isset($_POST["start_dt"]) && $_POST["start_dt"] != "" &&
            isset($_POST["end_dt"]) && $_POST["end_dt"] != "" &&
            isset($_POST["repeat"]) && $_POST["repeat"] != "" &&
            isset($_POST["ends"]) && $_POST["ends"] != "" &&
            isset($_POST["type"]) && $_POST["type"] != "") {
            $title = $_POST["title"];
            $desc = $_POST["desc"];
            $start_dt = $_POST["start_dt"];
            $end_dt = $_POST["end_dt"];
            $repeat = $_POST["repeat"];
            $ends = $_POST["ends"];
            $type = $_POST["type"];
    
            Task::create(
                $title,
                $desc,
                $start_dt,
                $end_dt,
                $repeat,
                $ends,
                $type
            );
            return;
        }
    }

    respond403();
?>