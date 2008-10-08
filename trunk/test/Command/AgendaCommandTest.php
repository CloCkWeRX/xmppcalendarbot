<?php
require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Gdata/Calendar.php';

require_once dirname(__FILE__) . '/../../CalendarBotApplication.php';
require_once dirname(__FILE__) . '/../../Handlers/MessageHandler.php';
require_once dirname(__FILE__) . '/../MockConnection.php';

class AgendaCommandTest extends PHPUnit_Framework_TestCase {

    public function testShouldCorrectlyFindEvents1() {

        $calendar = new DummyCalendar();
        $ac = new AgendaCommand($calendar);

        $this->assertEquals(new Zend_Gdata_Calendar_EventFeed(), $ac->findEvents($calendar, time()));
    }

    public function testShouldCorrectlyFindEvents2() {

        $calendar = new MockCalendar();
        $ac = new AgendaCommand($calendar);

        $eventFeed = $ac->findEvents($calendar, time());

        $this->assertSame(2, $eventFeed->count());
    }


    public function testShouldMessageWithResults1() {
        $calendar = new DummyCalendar();
        $ac = new AgendaCommand($calendar);

        $conn = new MockConnection();

        $args = array(null, '2008-01-03');

        $ac->exec($conn, $args, array(
            'from' => 'unit.test@testing.com'
        ));


        $messages = $conn->getMessages();

        $this->assertSame('unit.test@testing.com', $messages[0][0]);
        $this->assertSame('For 2008-01-03 you have 0 event(s)', $messages[0][1]);

        $this->assertSame(1, count($messages));
    }


    public function testShouldMessageWithResults2() {
        $calendar = new MockCalendar();
        $ac = new AgendaCommand($calendar);

        $conn = new MockConnection();

        $args = array(null, '2008-01-03');

        $ac->exec($conn, $args, array(
            'from' => 'unit.test@testing.com'
        ));


        $messages = $conn->getMessages();

        $this->assertSame('unit.test@testing.com', $messages[0][0]);
        $this->assertSame('For 2008-01-03 you have 2 event(s)', $messages[0][1]);

        $this->assertSame("1. Unit test entry 1 (2008-02-02 09:00:00am to 2008-02-02 10:00:00am)", $messages[1][1]);
        $this->assertSame("2. Unit test entry 2 (2008-03-02 07:00:00pm to 2008-03-02 08:00:00pm)", $messages[2][1]);

        $this->assertSame(3, count($messages));
    }

}

class DummyCalendar extends Zend_Gdata_Calendar {
    public function getCalendarEventFeed($query) {
        return new Zend_Gdata_Calendar_EventFeed();
    }
}

class MockCalendar extends Zend_Gdata_Calendar {
    public function getCalendarEventFeed($query) {
        $feed = new Zend_Gdata_Calendar_EventFeed();

        $entry = new Zend_Gdata_Calendar_EventEntry();
        $entry->setTitle("Unit test entry 1");
        $entry->setPublished('2008-01-01 9:00:00');
        $entry->when = array($this->newWhen('2008-02-02 9:00:00', '2008-02-02 10:00:00'));

        $feed->addEntry($entry);

        $entry = new Zend_Gdata_Calendar_EventEntry();
        $entry->setTitle("Unit test entry 2");
        $entry->setPublished('2008-01-01 9:00:00');
        $entry->when = array($this->newWhen('2008-03-02 19:00:00', '2008-03-02 20:00:00'));

        $feed->addEntry($entry);

        return $feed;
    }
}