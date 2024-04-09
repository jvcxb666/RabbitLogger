<?php

namespace App\Logger\Interface;

interface MessageInterface
{
    public function getContent(): string|null;
}