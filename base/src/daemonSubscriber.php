<?php

use App\Logger\Subscriber;

$pid = pcntl_fork();

if($pid) exit();
posix_setsid();

require_once "vendor/autoload.php";

$subscriber = new Subscriber();
$subscriber->subscribe("debug");
$subscriber->subscribe("fatal");
$subscriber->subscribe("warning");

while(true) { //=)
    $subscriber->consume();
    sleep(1);
}