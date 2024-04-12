<?php

namespace App\Interface;

interface Configurable
{

    public function getConfig(): array|null;
    
}