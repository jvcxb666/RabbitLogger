<?php

use App\Logger\Subscriber;
use App\Logger\Writer\FileWriter;

$pid = pcntl_fork();

if($pid) exit();
posix_setsid();

require_once "vendor/autoload.php";

$subscriber = new Subscriber(new FileWriter());
$subscriber->subscribe("debug");
$subscriber->subscribe("fatal");
$subscriber->subscribe("warning");

while(true) { //=)
    $subscriber->consume();
    sleep(1);
}