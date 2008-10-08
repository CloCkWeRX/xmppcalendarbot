<?php
if (!defined('PHPUNIT_MAIN_METHOD')) {
    define('PHPUNIT_MAIN_METHOD', 'CommandTestSuite::main');
}

require_once 'PHPUnit/TextUI/TestRunner.php';
require_once 'PHPUnit/Framework/TestSuite.php';

require_once 'AgendaCommandTest.php';

class CommandTestSuite {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {

        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTestSuite('AgendaCommandTest');

        return $suite;
    }
}

if (PHPUNIT_MAIN_METHOD == 'CommandTestSuite::main') {
    CommandTestSuite::main();
}
