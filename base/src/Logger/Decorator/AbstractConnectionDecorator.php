<?php

namespace App\Logger\Decorator;

use App\Logger\Interface\RabbitConnectable;

abstract class AbstractConnectionDecorator
{
    protected RabbitConnectable $client;

    public function __construct(RabbitConnectable $client)
    {
        $this->client = $client;
    }

    abstract public function setup(): void;
}