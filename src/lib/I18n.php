<?php

/**
 * This class handles internationalisation
 */
class I18n {

  private $directory;
  private $lang = Config::LANG;
  private $langObj;

  /**
   * Sets the language variable, and an alternative language file directory if unit testing.
   * @param $lang
   * @param $isUnitTest
   */
  public function __construct($lang = Config::LANG, $isUnitTest = false) {

    // Hacky way of running unit test
    // TODO: Research better way of unit test include paths
    if ($isUnitTest) {
      $this->directory = ROOT.DS.'..'.DS.'tests'.DS.'i18n'.DS;
    } else {
       $this->directory = ROOT.DS.'i18n'.DS;
    }

    $this->lang = $lang;
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
  public function setLang($lang) {
    $this->lang = $lang;
    $this->readi18nJSON($lang);
  }

  /**
   * This is the function to call when you wish to return an internationalised string.
   * @param $id
   * @param $lang
   * @returns String
   */
  public function getLocalisedString($id) {
    return $this->langObj[$id];
  }

}

/**
 * Makes a global __() function and instantiates the i18n object upon use.
 * TODO: Look at new I18n object language selection
 */
function __($id) {
  $i18n = new I18n();
  return $i18n->getLocalisedString($id);
}
