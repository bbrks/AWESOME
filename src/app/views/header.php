<!DOCTYPE html>
<html lang="<?php echo __('@ISO639-1'); ?>">
<head>

  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php isset($title) ? title($title) : title(); ?></title>

  <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />

</head>
<body>

<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="infoModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="infoModalLabel">About <span class="sr-only">AWESOME</span><img src="/assets/img/logo.png" alt="AWESOME" height="24px" /></h4>
      </div>
      <div class="modal-body">
        <p><?php echo __('about-awesome'); ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?php if (Config::DEBUG) { ?>
<div class="modal fade" id="feedbackModal" tabindex="-1" role="dialog" aria-labelledby="feedbackModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form role="form" method="post" action="/send-feedback.php">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="feedbackModalLabel">Send Feedback</h4>
        </div>
        <div class="modal-body">
          <p>AWESOME is currently in development and needs your help!<br />
          If you encounter any problems or want to leave feedback, please use this form.</p>
          <textarea name="feedbacktxt" class="form-control" rows="5" placeholder="Your feedback here&hellip;"></textarea>
          <input type="hidden" name="token" value="<?php echo $_GET['token'] ?>" />
        </div>
        <div class="modal-footer">
          <p class="pull-left">Issues may be followed up via E-Mail.</p>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          <input type="submit" name="submit" class="btn btn-danger" value="Send Feedback" />
        </div>
      </form>
    </div>
  </div>
</div>
<?php } ?>

<nav class="navbar navbar-default navbar-fixed-top">
  <div class="container">
    <div class="navbar-header">
      <a class="navbar-brand brand-awesome">
        <img src="/assets/img/logo.png" alt="AWESOME" />
        <span class="sr-only">AWESOME</span>
      </a>
    </div>
    <?php if (Config::DEBUG) { ?>
    <ul class="nav navbar-nav navbar-left">
      <li><button type="button" class="btn btn-danger navbar-btn" title="Problems or Suggestions? Click here!" data-toggle="modal" data-target="#feedbackModal"><?php echo __('fb-feedback') ?></button></li>
    </ul>
    <?php } ?>
    <ul class="nav navbar-nav navbar-right">
      <li><button type="button" class="btn btn-info navbar-btn" title="What is AWESOME?" data-toggle="modal" data-target="#infoModal"><?php echo __('about') ?></button></li>
      <?php if (sizeof(Config::$LANGUAGES) > 1) { ?>
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
            <span class="glyphicon glyphicon-flag" aria-hidden="true"></span>
            <?php echo __('@lang') . ' (' . __('@ISO639-1') . ')' ?>
            <span class="caret"></span>
          </a>
          <ul class="dropdown-menu" role="menu">
            <?php foreach (Config::$LANGUAGES as $lang_menu) { ?>
              <li><a href="<?php echo addOrUpdateUrlParam('lang', $lang_menu) ?>"><?php echo $lang_menu ?></a></li>
            <?php } ?>
          </ul>
        </li>
      <?php } ?>
    </ul>
  </div>
</nav>

<div class="container">

<?php if (Config::DEBUG) { ?>
<div class="alert alert-info alert-dismissible" role="alert">
  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
  <strong><?php echo __('app_title') . ' ' . __('fb-is-in-testing') ?></strong>
  <?php echo __('fb-bugs-or-feedback') .'&hellip;'. __('fb-hit-the') ?>
  <button type="button" class="btn btn-danger btn-xs" title="Problems or Suggestions? Click here!" data-toggle="modal" data-target="#feedbackModal"><?php echo __('fb-feedback') ?></button>
  <?php echo __('fb-button-above') ?></p>
</div>
<?php } ?>

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
