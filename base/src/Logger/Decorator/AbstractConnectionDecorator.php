<?php

namespace App\Logger\Decorator;

use App\Logger\Connection\BaseConnection;
use App\Logger\Interface\RabbitConnectable;

abstract class AbstractConnectionDecorator
{
    protected RabbitConnectable $client;

    public function __construct(RabbitConnectable $client = null)
    {
        if(empty($client)) $client = new BaseConnection();
        $this->client = $client;
    }

    abstract public function setup(): void;
}