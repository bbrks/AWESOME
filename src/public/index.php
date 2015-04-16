<?php

$url = $_GET['url'];

if (preg_match('/^questionnaires\/view\/[a-z0-9]{16}(\?.*)?$/', $url) == 0) {
  die('Invalid URL.');
}

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.php');
