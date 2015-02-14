<?php

/**
 * Define a few useful constants
 * @const DS Alias for DIRECTORY_SEPARATOR
 * @const ROOT Relative path to src
 */
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(__FILE__)));

/**
 * Require ./config/config.php and ./library/shared.php
 */
require_once(ROOT . DS . 'config' . DS . 'config.php');
require_once(ROOT . DS . 'lib' . DS . 'shared.php');
