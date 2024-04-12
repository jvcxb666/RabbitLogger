<?php

namespace App\Interface;

interface MessageInterface
{
    public function getContent(): string|null;
}