<?php

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
  if (isset($msg)) {
    echo '<div class="alert alert-success" role="alert">'.$msg.'</div>';
  }
?>
