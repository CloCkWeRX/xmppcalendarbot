<?php
class AgendaCommand implements MessageCommand {

    protected $calendar;

    public function __construct(Zend_Gdata_Calendar $calendar) {
        $this->calendar = $calendar;
    }

    public function exec($conn, $args, $data) {

        list(, $day) = $args;


        try {
            $start_timestamp = strtotime($day);
            $eventFeed = $this->findEvents($this->calendar, $start_timestamp);

            $conn->message($data['from'], "For " . date("Y-m-d", $start_timestamp) . " you have " . $eventFeed->count() . " event(s)");

            foreach ($eventFeed as $n => $event) {
                list($when) = $event->when;

                $start = date("Y-m-d h:i:sa", strtotime((string)$when->startTime));
                $end   = date("Y-m-d h:i:sa", strtotime((string)$when->endTime));

                $span = "("  . $start . " to " . $end . ")";

                $text = ($n+1) . ". " . (string)$event->title . " " . $span;

                $conn->message($data['from'], $text);
            }
        } catch (Exception $e) {
            $conn->message($data['from'], "Umm, something isn't right: " . $e->getMessage());
            $conn->message($data['from'], "Can you check my google account can access google calendar? You might need to login as me!");

            print $e;
        }


        return true;
    }

    public function findEvents($calendar, $start_timestamp) {
        $query = $calendar->newEventQuery();
        $query->setUser('default');

        $query->setVisibility('private');
        $query->setProjection('full');
        $query->setOrderby('starttime');

        $query->setStartMin(date("Y-m-d", $start_timestamp));
        $query->setStartMax(date("Y-m-d", $start_timestamp + 86400));

        return $calendar->getCalendarEventFeed($query);
    }

}