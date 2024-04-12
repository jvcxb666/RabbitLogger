<?php

namespace App\Decorator;

use App\Connection\BaseConnection;
use App\Interface\Configurable;
use App\Interface\RabbitConnectable;

abstract class AbstractConnectionDecorator implements Configurable
{
    protected RabbitConnectable $client;

    public function __construct(RabbitConnectable $client = null)
    {
        if(empty($client)) $client = new BaseConnection();
        $this->client = $client;
    }

    public function setup(): void
    {
        foreach($this->getConfig()['queues'] as $options)
        {
            $this->client->getChannel()->exchangeDeclare($options['exchange_name'],$options['exchange']);
            if(!empty($options['key']))
            {
                $this->client->getChannel()->queueDeclare($options['name']);
                $this->client->getChannel()->queueBind($options['name'],$options['exchange_name'],$options['key']);
            }else{
                foreach($options['queues'] as $q){
                    $this->client->getChannel()->queueDeclare($q);
                    $this->client->getChannel()->queueBind($q,$options['exchange_name']);
                }
            }
        }
    }
}