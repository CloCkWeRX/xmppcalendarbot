<?php
require_once 'CalendarBotApplication.php';


$user = 'you@gmail.com';
$pass = 'password';
$url  = dirname(__FILE__) . '/atom.xml';
$server = 'talk.google.com';

date_default_timezone_set('Australia/Adelaide');

// Or load from config
include 'config.php';




// And now the start
$app = new CalendarBotApplication($user, $pass, $server);
$app->run();


