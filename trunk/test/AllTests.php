<?php
if (!defined('PHPUNIT_MAIN_METHOD')) {
    define('PHPUNIT_MAIN_METHOD', 'CalendarBot_Tests_AllTests::main');
}

require_once 'PHPUnit/Framework/TestSuite.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once dirname(__FILE__) . '/Handler/HandlerTestSuite.php';
require_once dirname(__FILE__) . '/Command/CommandTestSuite.php';

class CalendarBot_Tests_AllTests {
    public static function main() {
        PHPUnit_TextUI_TestRunner::run(self::suite());
    }

    public static function suite() {
        $suite = new PHPUnit_Framework_TestSuite();
        $suite->addTest(HandlerTestSuite::suite());
        $suite->addTest(CommandTestSuite::suite());
        return $suite;
    }
}

if (PHPUNIT_MAIN_METHOD == 'CalendarBot_Tests_AllTests::main') {
    CalendarBot_Tests_AllTests::main();
}