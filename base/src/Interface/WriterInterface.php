<?php

namespace App\Interface;

interface WriterInterface
{
    public function writeLog(string|MessageInterface $message): void;
}