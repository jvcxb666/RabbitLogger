<?php

namespace App\Logger\Message;

use App\Logger\Interface\MessageInterface;

class LoggerStringMessage implements MessageInterface
{
    private string $message;

    public function __construct(string|array $message)
    {
        $this->createMessage($message);
    }

    public function getContent(): string|null
    {
        return $this->message ?? null;
    }

    private function createMessage(string|array $message): void
    {
        $date = date("d.m.Y h:i:s");

        if(is_array($message)){
            $message['logger_message_date'] = $date;
            $message = json_encode($message);
        }else{
            $message = "[{$date}] {$message}";
        }

        $this->message = $message;
    }
}