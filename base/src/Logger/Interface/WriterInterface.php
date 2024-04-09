<?php

namespace App\Logger\Interface;

interface WriterInterface
{
    public function writeLog(string $message): void;
}