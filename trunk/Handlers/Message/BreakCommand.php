<?php
class BreakCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        $conn->send("</end>");

        return true;
    }
}