<?php

namespace App\Logger\Interface;

use Bunny\Channel;

interface RabbitConnectable
{
    public function connect(): void;
    public function getChannel(): Channel;
}