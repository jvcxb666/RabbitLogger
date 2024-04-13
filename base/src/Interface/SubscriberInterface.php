<?php

namespace App\Interface;

interface SubscriberInterface
{
    public function subscribe(string $subscribe): void;
    public function unsubscribe(string $subscribe): void;
    public function consume(): void;
}