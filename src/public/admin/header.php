<?php require_once('../../config/config.php'); ?>
<!DOCTYPE html>
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
      <a class="navbar-brand brand-awesome" href=".">
        <img src="/assets/img/logo.png" alt="AWESOME" />
        <span class="sr-only">AWESOME Dashboard</span>
      </a>
    </div>
    <?php if (Config::DEBUG) { ?>
    <ul class="nav navbar-nav navbar-left">
      <li><button type="button" class="btn btn-danger navbar-btn" title="Problems or Suggestions? Click here!" data-toggle="modal" data-target="#feedbackModal">Feedback</button></li>
    </ul>
    <?php } ?>
    <?php if (sizeof(Config::LANGUAGES) > 1) { ?>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
          <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
          <span class="caret"></span>
        </a>
        <ul class="dropdown-menu" role="menu">
          <?php foreach (Config::LANGUAGES as $lang) { ?>
            <li><a href="#"><?php echo $lang ?></a></li>
          <?php } ?>
        </ul>
      </li>
    </ul>
    <?php } ?>
  </div>
</nav>

<div class="container">

<?php
  if (isset($error)) {
    echo '<div class="alert alert-danger" role="alert">'.$error.'</div>';
  }
?>
