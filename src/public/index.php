<?php

$url = isset($_GET['url']) ? $_GET['url'] : null;

if (preg_match('/^questionnaires\/view\/[a-z0-9]{16}(\?.*)?$/', $url) == 0) {
  header('Location: /admin');
}

require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'bootstrap.php');
