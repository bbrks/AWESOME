<?php

class I18nTest extends PHPUnit_Framework_TestCase {

    private $i18n;

    protected function setUp() {
        $this->i18n = new I18n('test', true);
    }

    /**
     * Runs getLocalisedString on a pre-defined test file.
     */
    public function testGetLocalisedString() {
        $str = $this->i18n->getLocalisedString('testid');
        $this->assertEquals($str, 'This is a test localisation phrase.');
    }

    /**
     * Runs getLocalisedString on a pre-defined test file.
     */
    public function testSetLang() {
        $str = $this->i18n->getLocalisedString('@lang');
        $this->assertEquals($str, 'Test File 1');

        $this->i18n->setLang('test2');

        $str = $this->i18n->getLocalisedString('@lang');
        $this->assertEquals($str, 'Test File 2');
    }

}
