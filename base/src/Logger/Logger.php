<?php

namespace App\Logger;

use App\Decorator\AbstractConnectionDecorator;
use App\Message\LoggerStringMessage;
use App\Utils\ConfigProvider;

class Logger extends AbstractConnectionDecorator
{
    public function __construct()
    {
        parent::__construct();
        $this->setup();
    }

    public function __call($name, $arguments): void
    {
        if(empty($arguments)) return;

        if(array_key_exists($name,$this->getConfig()['queues'])) {
            $this->publish($name,$arguments[0]);
        } else {
            $this->publish("all",$arguments[0]);
        }
    }

   public function getConfig(): array|null
   {
        return ConfigProvider::getConfigVariable("baseLogger");
   }

   private function publish(string $type,string|array $message): void
   {
        $config = $this->getConfig()['queues'][$type];
        $message = new LoggerStringMessage($message);
        if($type !== "all"){
            $this->client->getChannel()->publish($message->getContent(),[],$config['exchange_name'],$config['key']);
        } else {
            $this->client->getChannel()->publish($message->getContent(),[],$config['exchange_name']);
        }
   }
}