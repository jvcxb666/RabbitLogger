<?php

namespace App\Subscriber;

use App\Decorator\AbstractConnectionDecorator;
use App\Interface\Configurable;
use App\Interface\WriterInterface;
use App\Message\LoggerStringMessage;
use App\Utils\ConfigProvider;
use App\Writer\FileWriter;

class Subscriber extends AbstractConnectionDecorator implements Configurable
{
    private array $subscribed = [];
    private WriterInterface $writer;

    public function __construct(WriterInterface $writer = null)
    {
        parent::__construct();
        $this->setup();
        if(empty($writer)) $writer = new FileWriter();
        $this->writer = $writer;
    }

    public function setup(): void
    {
        foreach($this->getConfig()['level'] as $options)
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
        return ConfigProvider::getConfigVariable("baseLogger");
   }

   public function subscribe(string $subscribe): void
   {
        if(!in_array($subscribe,$this->subscribed) && array_key_exists($subscribe,$this->getConfig()['level'])) $this->subscribed[$subscribe] = $subscribe;
   }

   public function unsubscribe(string $subscribe): void
   {
        if(in_array($subscribe,$this->subscribed)) unset($this->subscribed[$subscribe]);
   }

   public function consume(): void
   {
        foreach($this->subscribed as $subscribe) {
            $msg = $this->client->getChannel()->get($subscribe);
            if(!empty($msg)) {
                $message = new LoggerStringMessage($msg->content,true);
                $this->client->getChannel()->ack($msg);
                $this->writer->writeLog(strtoupper($subscribe)." ".$message->getContent());
            }
        }
   }
}