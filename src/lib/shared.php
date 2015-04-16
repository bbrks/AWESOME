<?php

/**
 * Display errors if the DEBUG const is set in config.php
 * Otherwise log them in a file.
 */
function setReporting() {
  if (Config::DEBUG == true) {
    error_reporting(E_ALL);
    ini_set('display_errors', 'On');
  } else {
    error_reporting(E_ALL);
    ini_set('display_errors', 'Off');
    ini_set('log_errors', 'On');
    ini_set('error_log', ROOT.DS.'logs'.DS.'error.log');
  }
}

/**
 * Navigates through an array and removes slashes from the values.
 *
 * If an array is passed, the array_map() function causes a callback to pass the
 * value back to the function. The slashes from this value will removed.
 *
 * @param $value The value to be stripped.
 * @return $value The stripped value.
 *
 * @link https://core.trac.wordpress.org/browser/tags/4.1/src/wp-includes/formatting.php#L1697 stripslashes_deep() in WordPress core.
 */
function stripSlashesDeep($value) {
  if (is_array($value)) {
    $value = array_map('stripSlashesDeep', $value);
  } elseif (is_object($value)) {
    $vars = get_object_vars($value);
    foreach ($vars as $key=>$data) {
      $value->{$key} = stripSlashesDeep($data);
    }
  } elseif (is_string($value)) {
    $value = stripslashes($value);
  }

  return $value;
}

/**
 * Check Magic Quotes and remove them
 * Magic Quotes have been deprecated as of PHP 5.3.0 and removed in PHP 5.4.0
 */
function removeMagicQuotes() {
  if ( get_magic_quotes_gpc() ) {
    $_GET = stripSlashesDeep($_GET);
    $_POST = stripSlashesDeep($_POST);
    $_COOKIE = stripSlashesDeep($_COOKIE);
  }
}

/**
 * Check Register Globals and remove them
 * Register Globals have been deprecated as of PHP 5.3.0 and removed in PHP 5.4.0
 */
function unregisterGlobals() {
  if (ini_get('register_globals')) {
    $array = array('_SESSION', '_POST', '_GET', '_COOKIE', '_REQUEST', '_SERVER', '_ENV', '_FILES');
    foreach ($array as $value) {
      foreach ($GLOBALS[$value] as $key => $var) {
        if ($var === $GLOBALS[$key]) {
          unset($GLOBALS[$key]);
        }
      }
    }
  }
}

/**
 * Main Call function which loads the controller and model
 * URLs have the structure 'domain.tld/controllerName/actionName/queryString'
 *
 * @param $url The URL set via $_GET['url'] used for routing
 */
function callHook($url) {
  $urlArray = array();
  $urlArray = explode("/",$url);

  $controller = $urlArray[0];
  array_shift($urlArray);
  $action = $urlArray[0];
  array_shift($urlArray);
  $queryString = $urlArray;

  $controllerName = $controller;
  $controller = ucwords($controller);
  $model = rtrim($controller, 's');
  $controller .= 'Controller';
  $dispatch = new $controller($model,$controllerName,$action);

  if ((int)method_exists($controller, $action)) {
    call_user_func_array(array($dispatch,$action),$queryString);
  } else {
    error_log('Failed to call_user_func(array('.$dispatch.','.$action.'),'.$queryString.')');
  }
}

/**
 * Autoload any classes that are required
 * This function is automatically run upon Object creation
 *
 * @param string $className
 */
function __autoload($className) {
  if (file_exists(ROOT.DS.'lib'.DS.$className.'.php')) {
    require_once(ROOT.DS .'lib'.DS.$className.'.php');
  } else if (file_exists(ROOT.DS.'app'.DS.'controllers'.DS.$className.'.php')) {
    require_once(ROOT.DS.'app'.DS.'controllers'.DS.$className.'.php');
  } else if (file_exists(ROOT.DS.'app'.DS.'models'.DS.$className.'.php')) {
    require_once(ROOT.DS.'app'.DS.'models'.DS.$className.'.php');
  } else {
    error_log('Class '.$className.' could not be autoloaded.');
  }
}

setReporting();
removeMagicQuotes();
unregisterGlobals();
callHook($url);
