<?php

namespace App\Logger\Message;

use App\Logger\Interface\MessageInterface;

class LoggerStringMessage implements MessageInterface
{
    private string $message;

    public function __construct(string|array $message, bool $consumed = false)
    {
        (!$consumed) ? $this->createMessage($message) : $this->parseMessage($message);
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

    private function parseMessage(string $message): void
    {
        $decoded = json_decode($message,1);
        if(!empty($decoded) && is_array($decoded)) {
            $message = "[".$decoded['logger_message_date']."]";
            unset($decoded['logger_message_date']);
            $message .= " ".json_encode($decoded);
        }

        $this->message = $message;
    }
}