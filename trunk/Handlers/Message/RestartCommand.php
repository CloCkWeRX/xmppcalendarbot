<?php
class RestartCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        $quit = new QuitCommand();
        $quit->exec($conn, $args, $data);

        exec('php ' . __FILE__);
    }
}