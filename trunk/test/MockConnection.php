<?php
class MockConnection extends XMPPHP_XMPP {
    protected $data;

    public function __construct() {
    }

    public function message($to, $body, $type = 'chat', $subject = null, $payload = null) {
        $this->data[] = array($to, $body, $type, $subject, $payload);
    }

    public function getMessages() {
        return $this->data;
    }
}