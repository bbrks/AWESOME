<?php

if (!isset($_SERVER['PHP_AUTH_USER'])) {
    header('WWW-Authenticate: Basic realm="AWESOME Admin Dashboard"');
    header('HTTP/1.0 401 Unauthorized');
    echo 'The AWESOME Admin Dashboard requires a login.';
    exit;
} else {
    if ((!$_SERVER['PHP_AUTH_USER'] == "awesome1") && (!$_SERVER['PHP_AUTH_PW'] == "letmein5")) {
      die('Incorrect credentials entered.');
    }
}
