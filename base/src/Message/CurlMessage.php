<?php

namespace App\Message;

use App\Interface\MessageInterface;

class CurlMessage implements MessageInterface
{
    private string $url;
    private string $method = "POST";
    private array $body = [];
    private string $date = "";

    public function __construct(string $consumed = null)
    {
        if(!empty($consumed)){
            $consumed = json_decode($consumed,1);
            $this->setUrl($consumed['url'] ?? "");
            $this->setMethod($consumed['method'] ?? "");
            $this->setBody(json_decode($consumed['body'],1) ?? []);
            $this->date = $consumed['logger_message_date'] ?? $this->setDate();
        }
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function setMethod(string $method): void
    {
        $this->method = $method;
    }

    public function getBody(): string|null
    {
        return json_encode($this->body) ?? null;
    }

    public function setBody(array $body): void
    {
        $this->body = $body;
    }

    public function setDate(): void
    {
        $this->date = date("d.m.Y h:i:s");
    }

    public function getContent(): string|null
    {
        return json_encode([
            "url" => $this->getUrl(),
            "method" => $this->getMethod(),
            "body" => $this->getBody(),
            'logger_message_date' => $this->date,
        ]);
    }

    public function asString(): string
    {
        if(empty($this->date)) $this->setDate();
        return "CURL [".$this->date."] ".json_encode($this->getBody());
    }
}