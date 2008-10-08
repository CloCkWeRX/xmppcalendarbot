<?php
if (!defined('PHPUNIT_MAIN_METHOD')) {
    define('PHPUNIT_MAIN_METHOD', 'HandlerTestSuite::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'MessageHandlerTest.php';

class HandlerTestSuite {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('MessageHandlerTest');

        return $suite;
    }
}

if (PHPUNIT_MAIN_METHOD == 'HandlerTestSuite::main') {
    HandlerTestSuite::main();
}
