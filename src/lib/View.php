<?php

/**
 * The View class is used to render the view,
 * and fetch global headers and footers if no controller specific ones exist
 */
class View {

  protected $variables = array();
  protected $_controller;
  protected $_action;

  /**
   * Upon construction of the class, set controller and action
   *
   * @param $controller
   * @param $action
   */
  function __construct($controller, $action) {
    $this->_controller = $controller;
    $this->_action = $action;
  }

  /**
   * Set variables in the view
   *
   * @param $name
   * @param $value
   */
  function set($name, $value) {
    $this->variables[$name] = $value;
  }

  /**
   * Display the view. If no header or footer exists in view/controllerName,
   * then the global header and footer are used in view/
   */
  function render() {
    extract($this->variables);

    if (file_exists(ROOT.DS.'app'.DS.'views'.DS.$this->_controller.DS.'header.php')) {
      include(ROOT.DS.'app'.DS.'views'.DS.$this->_controller.DS.'header.php');
    } else {
      include(ROOT.DS.'app'.DS.'views'.DS.'header.php');
    }

    include(ROOT.DS.'app'.DS.'views'.DS.$this->_controller.DS.$this->_action.'.php');

    if (file_exists(ROOT.DS.'app'.DS.'views'.DS.$this->_controller.DS.'footer.php')) {
      include(ROOT.DS.'app'.DS.'views'.DS.$this->_controller.DS.'footer.php');
    } else {
      include(ROOT.DS.'app'.DS.'views'.DS.'footer.php');
    }

  }

  /**
   * Render the page title with a prefix if parameter is present
   *
   * @param $title
   */
  function title($title = null) {
    if ($title) {
      echo $title.' - '.Config::APP_TITLE;
    } else {
      echo Config::APP_TITLE;
    }
  }

}
