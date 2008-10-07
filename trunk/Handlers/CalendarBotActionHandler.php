<?php
interface CalendarBotActionHandler {
    public function dispatch($conn, $data);
}