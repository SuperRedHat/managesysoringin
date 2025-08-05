<?php

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION["islogin"]) || !$_SESSION["islogin"]) {
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: /login.php');
}
