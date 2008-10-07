<?php
require_once 'xmpphp/XMPPHP/XMPP.php';
require_once 'xmpphp/XMPPHP/Log.php';

class CalendarBotApplication {

    protected $user;
    protected $pass;
    protected $server;

    protected $actions = array();

    public function __construct($user, $pass, $server) {
        $this->user = $user;
        $this->pass = $pass;
        $this->server = $server;
        
        $this->actions["message"] = new MessageHandler($user, $pass);
        $this->actions["presence"] = new PresenceHandler();
        $this->actions["session_start"] = new LoginHandler();
    }


    public function run() {
        if (!extension_loaded('mbstring')) {
            throw new Exception("You are probably going to need the load the mbstring extension");
        }

        $conn = new XMPPHP_XMPP($this->server, 5222, $this->user, $this->pass, 'xmpphp', 'gmail.com');
        $conn->connect();


        while (!$conn->isDisconnected()) {
            $payloads = $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'));
            foreach ($payloads as $event) {
                $this->handleEvent($conn, $event);
            }
        }

        $conn->disconnect();
    }

    public function handleEvent($conn, $event) {

        list($action, $data) = $event;

        if ($this->actions[$action] instanceOf CalendarBotActionHandler) {
            $this->actions[$action]->dispatch($conn, $data);
        }
    }

}

function load_handler($class) {
    $path = dirname(__FILE__) . '/Handlers/' . $class . '.php';

    if (file_exists($path)) {
        include_once $path;
    }
}

spl_autoload_register('load_handler');
