<?php
class LoginHandler implements CalendarBotActionHandler {

    public function dispatch($conn, $data) {
         $conn->presence($status = "Johnny 5 is Alive!");
    }
}
