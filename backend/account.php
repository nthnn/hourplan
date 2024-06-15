<?php
    header("Access-Control-Allow-Origin: *");

    include_once("lib/db_config.php");
    include_once("lib/guidv4.php");
    include_once("lib/response.php");
    include_once("lib/session.php");
    include_once("lib/validator.php");

    class Account {
        public static function validate($hash) {
            if(!validateUuid($hash)) {
                respondFailed();
                return;
            }

            global $db_conn;
            $result = mysqli_query(
                $db_conn,
                "SELECT user_id FROM session WHERE ".
                    "hash=\"".$hash."\" AND ".
                    "addr=\"".$_SERVER["REMOTE_ADDR"]."\" AND ".
                    "user_agent=\"".$_SERVER["HTTP_USER_AGENT"]."\""
            );

            if(mysqli_num_rows($result) == 1) {
                $urow = mysqli_fetch_row($result);
                $res = mysqli_query(
                    $db_conn,
                    "SELECT username, theme FROM users WHERE ".
                        "id=".$urow[0]
                );

                if(!$res) {
                    respondError("Something went wrong.");
                    return;
                }
   
                $row = mysqli_fetch_row($res);
                $username = $row[0];
                $theme = $row[1];

                jsonResponse("{\"status\": \"1\", ".
                    "\"hash\": \"".$hash."\", ".
                    "\"username\": \"".$username."\", ".
                    "\"theme\": \"".$theme."\"}");
                return;
            }

            respondFailed();
        }

        public static function login($username, $password) {
            if(!validateUsername($username)) {
                respondError("Invalid username string.");
                return;
            }

            if(!validatePassword($password)) {
                respondError("Invalid password string.");
                return;
            }

            global $db_conn;
            $result = mysqli_query(
                $db_conn,
                "SELECT id, theme FROM users WHERE ".
                    "username=\"".$username."\" AND ".
                    "password=\"".$password."\""
            );

            if(mysqli_num_rows($result) != 1) {
                respondError("Invalid username and/or password.");
                return;
            }

            $row = mysqli_fetch_row($result);
            $id = $row[0];
            $theme = $row[1];

            $uuid = guidv4();
            $logRes = mysqli_query(
                $db_conn,
                "INSERT INTO session (user_id, hash, addr, user_agent) VALUES".
                    "(".$id.", \"".$uuid."\", \"".
                    $_SERVER["REMOTE_ADDR"]."\", \"".
                    $_SERVER["HTTP_USER_AGENT"]."\")"
            );

            if(!$logRes) {
                respondError("Something went wrong.");
                return;
            }

            jsonResponse("{\"status\": \"1\", ".
                "\"hash\": \"".$uuid."\", ".
                "\"theme\": \"".$theme."\"}");
        }

        public static function logout($hash) {
            if(!validateUuid($hash)) {
                respondFailed();
                return;
            }

            global $db_conn;
            $result = mysqli_query(
                $db_conn,
                "DELETE FROM session WHERE ".
                    "hash=\"".$hash."\""
            );

            if(!$result) {
                respondFailed();
                return;
            }

            respondOk();
        }

        public static function signup($username, $email, $password) {
            if(!validateUsername($username)) {
                respondError("Invalid username string.");
                return;
            }

            if(!validateEmail($email)) {
                respondError("Invalid email address.");
                return;
            }

            if(!validatePassword($password)) {
                respondError("Invalid password string.");
                return;
            }

            global $db_conn;
            $checkRes = mysqli_query(
                $db_conn,
                "SELECT * FROM users WHERE ".
                    "username=\"".$username."\" OR ".
                    "email=\"".$email."\""
            );

            if(mysqli_num_rows($checkRes) > 0) {
                respondError("Username and/or email is already in-use.");
                return;
            }

            $result = mysqli_query(
                $db_conn,
                "INSERT INTO users (username, email, password, theme) VALUES".
                    "(\"".$username."\", \"".$email."\", \"".$password."\", 0)"
            );

            if($result) {
                Account::login($username, $password);
                return;
            }

            respondError("Something went wrong.");
        }

        public static function fetchEmail($hash) {
            if(!validateUuid($hash)) {
                respondFailed();
                return;
            }

            global $db_conn;
            $sessionId = getSessionUserId($hash);

            if($sessionId < 0) {
                respondError("Invalid session user ID.");
                return;
            }

            $result = mysqli_query(
                $db_conn,
                "SELECT email FROM users WHERE id=".$sessionId
            );

            jsonResponse(json_encode(mysqli_fetch_all($result)[0]));
            return;
        }
    }

    if($_SERVER["REQUEST_METHOD"] === "POST") {
        if(!isset($_POST["action"]) || empty($_POST["action"])) {
            respond403();
            return;
        }

        $action = $_POST["action"];
        if($action == "login" &&
            isset($_POST["username"]) && !empty($_POST["username"]) &&
            isset($_POST["password"]) && !empty($_POST["password"])) {
            $username = $_POST["username"];
            $password = $_POST["password"];
    
            Account::login($username, $password);
            return;
        }
        else if($action === "signup" &&
            isset($_POST["username"]) && !empty($_POST["username"]) &&
            isset($_POST["email"]) && !empty($_POST["email"]) &&
            isset($_POST["password"]) && !empty($_POST["password"])) {
            $username = $_POST["username"];
            $email = $_POST["email"];
            $password = $_POST["password"];

            Account::signup($username, $email, $password);
            return;
        }
        else if($action === "logout" &&
            isset($_POST["hash"]) && !empty($_POST["hash"])) {
            $hash = $_POST["hash"];

            Account::logout($hash);
            return;
        }
        else if($action === "validate" &&
            isset($_POST["hash"]) && !empty($_POST["hash"])) {
            $hash = $_POST["hash"];

            Account::validate($hash);
            return;
        }
        else if($action === "fetch_email" &&
            isset($_POST["hash"]) && !empty($_POST["hash"])) {
            $hash = $_POST["hash"];

            Account::fetchEmail($hash);
            return;
        }
    }

    respond403();
?>