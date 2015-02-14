<?php

/**
 * Contains configuration required by the system.
 *
 * ATTENTION: Copy/rename this file to config.php before modifying!
 *
 * @author Ben Brooks (beb12@aber.ac.uk)
 * @version 1.1-alpha
 */
class Config {

  /**
   * Constants to store database credentials.
   * @const DB_USER Database username
   * @const DB_PASS Database password
   * @const DB_HOST Database hostname[:port] (Default: 'localhost')
   * @const DB_NAME Database name (Default: 'awesome')
   * @const DB_PREFIX Table prefix (Default: 'awe_')
   */
  const DB_USER = '';
  const DB_PASS = '';
  const DB_HOST = 'localhost';
  const DB_NAME = 'awesome';
  const TB_PREFIX = 'awe_';

  /*******************************************************************
   ** DANGER: DO NOT MODIFY BELOW UNLESS YOU KNOW WHAT YOU'RE DOING **
   *******************************************************************/

  /**
   * @const DEBUG Boolean to enable production-dangerous features, like display errors.
   */
  const DEBUG = true;

}
