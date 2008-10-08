<?php
class WhenCommand implements MessageCommand {

    protected $calendar;

    public function __construct(Zend_Gdata_Calendar $calendar) {
        $this->calendar = $calendar;
    }

    public function exec($conn, $args, $data) {

        array_shift($args);
        array_shift($args);

        $text = implode(" ", $args);

        if (empty($text)) {
            $conn->message($data['from'], "Try 'when is (something)");

            return true;
        }



        try {
            $eventFeed = $this->findEvents($this->calendar, $text);

            $conn->message($data['from'], "Found you have " . $eventFeed->count() . " event(s) for " . $text);

            foreach ($eventFeed as $n => $event) {
                $span = array();
                foreach ($event->when as $when) {

                    $start = date("Y-m-d h:i:sa", strtotime((string)$when->startTime));
                    $end   = date("Y-m-d h:i:sa", strtotime((string)$when->endTime));
                    $span[] = $start . " to " . $end;
                }

                $text = ($n+1) . ". " . (string)$event->title . " (" . implode(", ", $span) . ")";

                $conn->message($data['from'], $text);
            }
        } catch (Exception $e) {
            $conn->message($data['from'], "Umm, something isn't right: " . $e->getMessage());
            $conn->message($data['from'], "Can you check my google account can access google calendar? You might need to login as me!");

            print $e;
        }


        return true;
    }

    public function findEvents($calendar, $text) {
        $query = $calendar->newEventQuery();
        $query->setUser('default');

        $query->setVisibility('private');
        $query->setProjection('full');
        $query->setOrderby('starttime');

        $query->setQuery($text);

        return $calendar->getCalendarEventFeed($query);
    }

}