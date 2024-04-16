<?php

namespace App\Subscriber;

use App\Decorator\AbstractConnectionDecorator;
use App\Interface\SubscriberInterface;
use App\Interface\WriterInterface;
use App\Writer\FileWriter;

abstract class AbstractSubscriber extends AbstractConnectionDecorator implements SubscriberInterface
{
    protected array $subscribed = [];
    protected WriterInterface $writer;

    public function __construct(WriterInterface $writer = null)
    {
        parent::__construct();
        $this->setup();
        if(empty($writer)) $writer = new FileWriter();
        $this->writer = $writer;
    }

    public function subscribe(string $subscribe): void
    {
        if(!in_array($subscribe,$this->subscribed) && array_key_exists($subscribe,$this->getConfig()['queues'])) $this->subscribed[$subscribe] = $subscribe;
    }
 
    public function unsubscribe(string $subscribe): void
    {
        if(in_array($subscribe,$this->subscribed)) unset($this->subscribed[$subscribe]);
    }
}