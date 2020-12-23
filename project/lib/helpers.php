<?php
session_start();//we can start our session here so we don't need to worry about it on other pages
require_once(__DIR__ . "/db.php");
//this file will contain any helpful functions we create
//I have provided two for you
function is_logged_in() {
    return isset($_SESSION["user"]);
}

function getBalance() {
    if (is_logged_in() && isset($_SESSION["user"]["points"])) {
        return $_SESSION["user"]["points"];
    }
    return 0;
}

function has_role($role) {
    if (is_logged_in() && isset($_SESSION["user"]["roles"])) {
        foreach ($_SESSION["user"]["roles"] as $roles) {
            if ($roles["name"] == $role) {
                return true;
            }
        }
    }
    return false;
}

function get_status() {
    if (is_logged_in() && isset($_SESSION["user"]["status"])) {
        return $_SESSION["user"]["status"];
    }
    return "";
}

function get_username() {
    if (is_logged_in() && isset($_SESSION["user"]["username"])) {
        return $_SESSION["user"]["username"];
    }
    return "";
}

function get_email() {
    if (is_logged_in() && isset($_SESSION["user"]["email"])) {
        return $_SESSION["user"]["email"];
    }
    return "";
}

function get_user_id() {
    if (is_logged_in() && isset($_SESSION["user"]["id"])) {
        return $_SESSION["user"]["id"];
    }
    return -1;
}

function safer_echo($var) {
    if (!isset($var)) {
        echo "";
        return;
    }
    echo htmlspecialchars($var, ENT_QUOTES, "UTF-8");
}

function flash($msg) {
    if (isset($_SESSION['flash'])) {
        array_push($_SESSION['flash'], $msg);
    }
    else {
        $_SESSION['flash'] = array();
        array_push($_SESSION['flash'], $msg);
    }

}

function getMessages() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        $_SESSION['flash'] = array();
        return $flash;
    }
    return array();
}

//Milestone 2
function top10week(){

}

//Milestone 2
function top10month(){

}

//Milestone 2
function top10lifetime(){

}

function pageMaker($qString, $scoreCount)
{
	
}

//end flash
?>
