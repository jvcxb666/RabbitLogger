<?php

namespace App\Logger;

use App\Decorator\AbstractConnectionDecorator;
use App\Message\CurlMessage;
use App\Utils\ConfigProvider;

class CurlLogger extends AbstractConnectionDecorator
{
    public function __construct()
    {
        parent::__construct();
        $this->setup();
    }

    public function getConfig(): array|null
    {
        return ConfigProvider::getConfigVariable("curlLogger");
    }

    public function send(CurlMessage $message, string $qname): void
    {
        $cfg = $this->getConfig()['queues'][$qname];
        $message->setDate();
        $this->client->getChannel()->publish($message->getContent(),[],$cfg['exchange_name'],$cfg['key']);
    }
}