<?php

use App\Logger\Logger;

require_once "vendor/autoload.php";

$logger = new Logger();
$logger->any("new");
$logger->fatal(["some array shit"]);