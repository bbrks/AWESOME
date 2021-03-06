<?php

/**
 * Get language parameter, or use default language if not set.
 * Instantiate a global i18n object
 */
$lang = isset($_GET['lang']) ? $_GET['lang'] : Config::DEFAULT_LANG;
$i18n = new I18n($lang);

/**
 * This class handles internationalisation
 */
class I18n {

  private $directory;
  private $langObj;

  /**
   * Sets the language variable, and an alternative language file directory if unit testing.
   * @param $lang
   * @param $isUnitTest
   */
  public function __construct($lang, $isUnitTest = false) {

    // Hacky way of running unit test
    // TODO: Research better way of unit test include paths
    if ($isUnitTest) {
      $this->directory = ROOT.DS.'..'.DS.'tests'.DS.'i18n'.DS;
    } else {
       $this->directory = ROOT.DS.'i18n'.DS;
    }

    $this->setLang($lang);

  }

  /**
   * This function will read a JSON file for a given language code.
   * @param $lang
   * @returns boolean Returns true on success.
   */
  private function readi18nJSON($lang) {
    $path = $this->directory . $lang . '.json';
    $json = file_get_contents($path);

    $this->langObj = json_decode($json, true);

    if (sizeof($this->langObj) > 0) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Changes the language used
   */
  public function setLang($lang_param) {
    global $lang;
    $lang = $lang_param;
    $this->readi18nJSON($lang);
  }

  /**
   * This is the function to call when you wish to return an internationalised string.
   * @param $id
   * @returns String
   */
  public function getLocalisedString($id) {
    return isset($this->langObj[$id]) ? $this->langObj[$id] : $id;
  }

}

/**
 * Makes a global __() function
 */
function __($id) {
  global $i18n;
  return $i18n->getLocalisedString($id);
}
