<?php

namespace App\Subscriber;

use App\Interface\WriterInterface;
use App\Message\CurlMessage;
use App\Utils\ConfigProvider;
use App\Writer\CurlWriter;

class CurlSubscriber extends AbstractSubscriber
{
    public function __construct(WriterInterface $writer = null)
    {
        parent::__construct();
        if(empty($writer)) $this->writer = new CurlWriter;
        $this->setup();
    }

    public function getConfig(): array|null
    {
        return ConfigProvider::getConfigVariable("curlLogger");
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