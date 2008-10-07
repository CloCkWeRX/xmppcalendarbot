<?php
class AddEventCommand implements MessageCommand {

    protected $calendar;

    public function __construct(Zend_Gdata_Calendar $calendar) {
        $this->calendar = $calendar;
    }

    public function exec($conn, $args, $data) {

        //Args
        array_shift($args);
        $event_text = implode(' ', $args);


        try {
            $event = $this->createQuickAddEvent($this->calendar, $event_text);

            $conn->message($data['from'], "Mmm hmm, got it.");

            $email = $this->calendar->newEmail($data['from']);

            $event = $this->setReminderOnEvent($this->calendar, $event);

            /** @todo   Figure out wtf to do here so I don't 404 */
//            $event->who = array($this->calendar->newWho(null, $email));
//            $event->author = array($this->calendar->newAuthor(null, $email));
            $event->save();

            $conn->message($data['from'], "Ok: I've booked it in, you'll get a reminder if you have set it up and I'm sharing my calendar with you");


        } catch (Exception $e) {
            $conn->message($data['from'], "Umm, something isn't right: " . $e->getMessage());
            $conn->message($data['from'], "Can you check my google account can access google calendar? You might need to login as me!");

            print $e;
        }


        return true;
    }

    public function createQuickAddEvent($calendar, $quickAddText) {
        $event = $calendar->newEventEntry();
        $event->content = $calendar->newContent($quickAddText);
        $event->quickAdd = $calendar->newQuickAdd(true);

        $newEvent = $calendar->insertEvent($event);

        return $newEvent;
    }

    public function setReminderOnEvent($calendar, $event, $method = "email", $minutes = "30") {
        list($when) = $event->when;

        $reminder = $calendar->newReminder();
        $reminder->method = $method;
        $reminder->minutes = "30";
        $when->reminders = array($reminder);

        return $event;
    }
}