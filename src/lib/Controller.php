<?php

/**
 * The Controller class is used for communication
 * between the controller, the model and the view
 */
class Controller {

  protected $_model;
  protected $_controller;
  protected $_action;
  protected $_view;

  /**
   * Upon construction of the class, create objects for the model and view.
   *
   * @param $model
   * @param $controller
   * @param $action
   */
  function __construct($model, $controller, $action) {
    $this->_controller = $controller;
    $this->_action = $action;
    $this->_model = $model;

    // Assign by reference
    $this->$model = new $model;
    $this->_view = new View($controller, $action);
  }

  /**
   * Set variables in the view
   *
   * @param $name
   * @param $value
   */
  function set($name, $value) {
    $this->_view->set($name, $value);
  }

  /**
   * Upon destruction of the class, display the view
   */
  function __destruct() {
    $this->_view->render();
  }

}
