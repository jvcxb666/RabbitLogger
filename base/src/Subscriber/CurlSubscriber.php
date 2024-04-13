<?php

namespace App\Subscriber;

use App\Decorator\AbstractConnectionDecorator;
use App\Interface\SubscriberInterface;
use App\Interface\WriterInterface;
use App\Message\CurlMessage;
use App\Utils\ConfigProvider;
use App\Writer\CurlWriter;

class CurlSubscriber extends AbstractConnectionDecorator implements SubscriberInterface
{
    private WriterInterface $writer;
    private array $subscribed = [];

    public function __construct(WriterInterface $writer = null)
    {
        if(empty($writer)) $this->writer = new CurlWriter;
        parent::__construct();
        $this->setup();
    }

    public function getConfig(): array|null
    {
        return ConfigProvider::getConfigVariable("curlLogger");
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
                $message = new CurlMessage($msg->content);
                $this->client->getChannel()->ack($msg);
                $this->writer->writeLog($message);
            }
        }
   }

}