<!DOCTYPE html>
<html lang="<?php echo __('@ISO639-1'); ?>">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php isset($title) ? $this->title($title) : $this->title(); ?></title>

  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

</head>
<body>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand brand-awesome" href="#">
        <img src="/assets/img/logo.png" alt="AWESOME" />
        <span class="sr-only">AWESOME</span>
      </a>
    </div>
    <?php if (sizeof(Config::LANGUAGES) > 1) { ?>
    <ul class="nav navbar-nav navbar-right">
      <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
          <span class="glyphicon glyphicon-globe" aria-hidden="true"></span>
          <?php echo __('@lang') . ' (' . __('@ISO639-1') . ')' ?>
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
