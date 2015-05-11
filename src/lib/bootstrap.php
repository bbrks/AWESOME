<?php

/**
 * Provides ROOT and DS constants for directory traversal
 */
require_once(dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'utils.php');

/**
 * Load the config and i18n class.
 */
require_once(ROOT . DS . 'config' . DS . 'config.php');
require_once(ROOT . DS . 'lib' . DS . 'I18n.php');
require_once(ROOT . DS . 'lib' . DS . 'Database.php');
