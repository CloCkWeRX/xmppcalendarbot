<?php
require_once 'PHPUnit/Framework/TestCase.php';

require_once dirname(__FILE__) . '/../.../CalendarBotApplication.php';
require_once dirname(__FILE__) . '/../.../Handlers/MessageHandler.php';
require_once dirname(__FILE__) . '/../MockConnection.php';


class MessageHandlerTest extends PHPUnit_Framework_TestCase {

    public function setUp() {
        $commands = array(
                        'example' => new ExampleTestCommand(),
                        'test'    => new AnotherTestCommand(),
                        'error'   => new ErrorTestCommand()
                    );
        $this->mh = new MessageHandler(null, null, $commands);


        $this->conn = new MockConnection();
    }

    public function testShouldInvokeTheCorrectCommand() {
        $this->mh->dispatch($this->conn, array(
            'body' => 'test Monkeyfish Cat',
            'from' => null,
            'type' => 'mystery'
        ));

        $this->mh->dispatch($this->conn, array(
            'body' => 'example Catfish Monkey',
            'from' => null,
            'type' => 'mystery'
        ));

        $messages = $this->conn->getMessages();

        $this->assertSame('AnotherTestCommand', $messages[0][1]);
        $this->assertSame('ExampleTestCommand', $messages[1][1]);
    }

    public function testShouldPolitelyRefuseUnknownCommands() {
        $this->mh->dispatch($this->conn, array(
            'body' => 'mystery Monkeyfish Cat',
            'from' => null,
            'type' => 'mystery'
        ));

        $messages = $this->conn->getMessages();

        $this->assertSame('Thanks for sending me "mystery Monkeyfish Cat".', $messages[0][1]);
    }

    public function testShouldPolitelyInformOfErrors() {
        $this->mh->dispatch($this->conn, array(
            'body' => 'error Monkeyfish Cat',
            'from' => null,
            'type' => 'mystery'
        ));

        $messages = $this->conn->getMessages();

        $this->assertSame('Whoops: I Broke', $messages[0][1]);
    }
}

class ExampleTestCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        $conn->message(null, get_class($this));
    }
}
class AnotherTestCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        $conn->message(null, get_class($this));
    }
}

class ErrorTestCommand implements MessageCommand {
    public function exec($conn, $args, $data) {
        throw new Exception("I Broke");
    }
}
