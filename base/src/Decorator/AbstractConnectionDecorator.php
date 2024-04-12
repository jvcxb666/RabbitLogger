<?php

namespace App\Decorator;

use App\Connection\BaseConnection;
use App\Interface\RabbitConnectable;

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