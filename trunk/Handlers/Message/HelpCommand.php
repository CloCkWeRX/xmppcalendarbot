<?php
class HelpCommand implements MessageCommand {

    protected $message_handler;

    public function __construct(MessageHandler $message_handler) {
        $this->message_handler = $message_handler;
    }

    public function exec($conn, $args, $data) {
        $conn->message($data['from'], "Commands available: " . implode(', ', array_keys($this->message_handler->getCommands())));

        return true;
    }
}