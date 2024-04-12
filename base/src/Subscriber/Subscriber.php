<?php

namespace App\Subscriber;

use App\Decorator\AbstractConnectionDecorator;
use App\Interface\WriterInterface;
use App\Message\LoggerStringMessage;
use App\Utils\ConfigProvider;
use App\Writer\FileWriter;

class Subscriber extends AbstractConnectionDecorator
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

   public function getConfig(): array|null
   {
        return ConfigProvider::getConfigVariable("baseLogger");
   }

   public function subscribe(string $subscribe): void
   {
        if(!in_array($subscribe,$this->subscribed) && array_key_exists($subscribe,$this->getConfig()['queues'])) $this->subscribed[$subscribe] = $subscribe;
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