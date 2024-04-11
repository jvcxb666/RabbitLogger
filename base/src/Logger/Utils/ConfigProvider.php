<?php

namespace App\Logger\Utils;

class ConfigProvider
{

    private static self $instance;
    private static array $config;

    public static function getConfigVariable(string $key): mixed
    {

        $instance = self::getInstance();
        return $instance::getConfig()[$key];
    }

    private static function getConfig(): array|null
    {
        return self::$config;
    }

    private static function getInstance(): self
    {
        if(empty(self::$instance)) return new ConfigProvider();
        return self::$instance;
    }

    private function __construct()
    {
        self::$config = include __DIR__."/../../../config.php";
        self::$instance = $this;
    }

}