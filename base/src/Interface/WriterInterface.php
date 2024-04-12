<?php

namespace App\Interface;

interface WriterInterface
{
    public function writeLog(string $message): void;
}