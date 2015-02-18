<?php

/**
 * The Model class is an abstraction layer on the Database class
 */
class Model extends Database {

  protected $_model;

  function __construct() {
    $this->_model = get_class($this);
    $this->_table = strtolower($this->_model)."s";
  }

  function __destruct() {

  }

}
