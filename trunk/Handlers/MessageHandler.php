<?php
require_once 'Zend/GData.php';
require_once 'Zend/GData/ClientLogin.php';
require_once 'Zend/GData/Calendar.php';

class MessageHandler implements CalendarBotActionHandler {

    protected $commands;

    public function __construct($user, $pass) {
        $this->commands['quit'] = new QuitCommand();
        $this->commands['restart'] = new RestartCommand();
        $this->commands['help'] = new HelpCommand($this);
        $this->commands['news'] = new NewsCommand();
        $this->commands['break'] = new BreakCommand();


        //Google services FTW!
        $client = Zend_Gdata_ClientLogin::getHttpClient($user, $pass, Zend_Gdata_Calendar::AUTH_SERVICE_NAME);
        $calendar = new Zend_Gdata_Calendar($client);

        $this->commands['agenda']   = new AgendaCommand($calendar);
        $this->commands['reminder'] = new AddEventCommand($calendar);
        $this->commands['when'] = new WhenCommand($calendar);
    }

    public function getCommands() {
        return $this->commands;
    }

    public function dispatch($conn, $data) {
        print "---------------------------------------------------------------------------------\n";
        print "Message from: {$data['from']}\n";
        if (!empty($data['subject'])) {
            print "Subject: {$data['subject']}\n";
        }
        print $data['body'] . "\n";
        print "---------------------------------------------------------------------------------\n";



        $args = explode(' ', $data['body']);
        list($command) = $args;


        if (isset($this->commands[$command]) && $this->commands[$command] instanceOf MessageCommand) {
            try {
                $this->commands[$command]->exec($conn, $args, $data);
            } catch (Exception $e) {
                $conn->message($data['from'], "Whoops: " . $e->getMessage());

                print $e;
            }
        } else {
            if (!empty($data['body'])) {
                $conn->message($data['from'], "Thanks for sending me \"{$data['body']}\".", $data['type']);
            }
        }

        return true;
    }
}

function load_command($class) {
    @include_once dirname(__FILE__) . '/Message/' . $class . '.php';
}

spl_autoload_register('load_command');
