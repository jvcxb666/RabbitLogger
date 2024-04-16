<?php

namespace App\Subscriber;

use App\Interface\WriterInterface;
use App\Message\LoggerStringMessage;
use App\Utils\ConfigProvider;
use App\Writer\FileWriter;

class Subscriber extends AbstractSubscriber
{
   public function getConfig(): array|null
   {
        return ConfigProvider::getConfigVariable("baseLogger");
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