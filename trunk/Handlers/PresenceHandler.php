<?php
class PresenceHandler implements CalendarBotActionHandler {

    public function dispatch($conn, $data) {
        print "Presence: {$data['from']} [{$data['show']}] {$data['status']}\n";
    }
}
