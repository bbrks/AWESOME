<?php

if (get_magic_quotes_gpc()) {
    $process = array(&$_GET, &$_POST, &$_COOKIE, &$_REQUEST);
    while (list($key, $val) = each($process)) {
        foreach ($val as $k => $v) {
            unset($process[$key][$k]);
            if (is_array($v)) {
                $process[$key][stripslashes($k)] = $v;
                $process[] = &$process[$key][stripslashes($k)];
            } else {
                $process[$key][stripslashes($k)] = stripslashes($v);
            }
        }
    }
    unset($process);
}

require_once('auth.php');
require_once('survey/functions.php');

set_include_path(dirname(dirname(dirname(__FILE__))));

require_once('config/config.php');
require_once('lib/Database.php');

?><!DOCTYPE html>
<html lang="en">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>AWESOME Dashboard</title>

  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand brand-awesome" href="/admin/">
        <img src="/assets/img/logo.png" alt="AWESOME" />
        <span class="sr-only">AWESOME Dashboard</span>
      </a>
    </div>
  </div>
</nav>

<div class="container">

<?php
  if (isset($error)) {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
  }
?>

<?php
  if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
  }
  if (isset($msg)) {
    echo '<div class="alert alert-success" role="alert">'.$msg.'</div>';
  }
?>
