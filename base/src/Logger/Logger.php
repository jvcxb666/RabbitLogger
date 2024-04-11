<?php

namespace App\Logger;

use App\Logger\Connection\BaseConnection;
use App\Logger\Decorator\AbstractConnectionDecorator;
use App\Logger\Interface\Configurable;
use App\Logger\Interface\RabbitConnectable;
use App\Logger\Message\LoggerStringMessage;
use App\Logger\Utils\ConfigProvider;

class Logger extends AbstractConnectionDecorator implements Configurable
{
    public function __construct(RabbitConnectable $connection = null)
    {
        if(empty($connection)) $connection = new BaseConnection();
        parent::__construct($connection);
        $this->setup();
    }

    public function __call($name, $arguments): void
    {
        if(empty($arguments)) return;

        if(array_key_exists($name,$this->getConfig())) {
            $this->publish($name,$arguments[0]);
        } else {
            $this->publish("all",$arguments[0]);
        }
    }

    public function setup(): void
    {
        foreach($this->getConfig() as $options)
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

   public function getConfig(): array|null
   {
        return ConfigProvider::getConfigVariable("baseLogger")['level'];
   }

   private function publish(string $type,string|array $message): void
   {
        $config = $this->getConfig()[$type];
        $message = new LoggerStringMessage($message);
        if($type !== "all"){
            $this->client->getChannel()->publish($message->getContent(),[],$config['exchange_name'],$config['key']);
        } else {
            $this->client->getChannel()->publish($message->getContent(),[],$config['exchange_name']);
        }
   }
}