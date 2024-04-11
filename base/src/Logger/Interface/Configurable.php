<?php

namespace App\Logger\Interface;

interface Configurable
{

    public function getConfig(): array|null;
    
}