<?php

/**
 * Contains configuration required by the system.
 *
 * ATTENTION: Copy/rename this file to config.php before modifying!
 *
 * @author Ben Brooks (beb12@aber.ac.uk)
 * @version 1.2
 */
class Config {



  //============================================================================
  // Database
  //============================================================================
  /**
   * Constants to store database credentials.
   * @const DB_USER Database username
   * @const DB_PASS Database password
   * @const DB_HOST Database hostname[:port] (Default: 'localhost')
   * @const DB_NAME Database name (Default: 'awesome')
   */
  const DB_USER = '';
  const DB_PASS = '';
  const DB_HOST = 'localhost';
  const DB_NAME = 'awesome';

  // E.g. http://awesome.aber.ac.uk
  const BASE_URL = '';



  //============================================================================
  // E-Mail
  //============================================================================
  const MAIL_DOMAIN = 'aber.ac.uk'; // The domain that gets appended to student ID

  const MAIL_FROM_ADDR = ''; // Aber restricts this to username
  const MAIL_FROM_NAME = 'AWESOME';

  //===============//
  // SMTP Settings //
  //===============//
  const SMTP_USERNAME = '';
  const SMTP_PASSWORD = '';
  const SMTP_HOST     = 'smtp.office365.com';
  const SMTP_PORT     = 587;
  const SMTP_SECURE   = 'tls';



  //============================================================================
  // Internationalisation/Multilingual
  //============================================================================
  /**
   * @var $LANGUAGES Array of ISO 639-1 codes to be read in from i18n directory
   * @const DEFAULT_LANG Default language to be used
   */
  static $LANGUAGES = array('en', 'cy');
  const DEFAULT_LANG = 'en';



  /**
   * @const DEBUG Boolean to enable debug and testing features, like display errors.
   */
  const DEBUG = true;

}
