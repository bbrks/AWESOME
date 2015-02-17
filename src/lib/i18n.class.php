<?php

/**
 * This class handles internationalisation
 */
class i18n {

  protected $directory = ROOT.DS.'i18n'.DS;
  public $lang;

  public function __construct($lang = Config::DEFAULT_LANG) {
    $this->lang = $lang;
  }

  public function __destruct() {

  }

  private function readJSON($lang_code) {

  }

  private function getTranslation($id) {
    echo 'getTranslation('.$id.')';
  }

  public function __($id, $lang) {
    $this->getTranslation($id);
    // echo $var;
  }

}

function __($id, $lang = Config::DEFAULT_LANG) {
  $i18n = new i18n($lang);
  $i18n->__($id, $i18n->lang);
}

__('test');
__('test', 'cy');
