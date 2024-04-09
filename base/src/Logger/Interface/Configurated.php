<?php

namespace App\Logger\Interface;

interface Configurated
{
    public const QCONFIG = [
        "level" => [
            "debug" => [
                "name" => "debug",
                "exchange_name" => "logger.topic",
                "exchange" => "topic",
                "key" => "logger.level.debug"
            ],
            "fatal" => [
                "name" => "fatal",
                "exchange_name" => "logger.topic",
                "exchange" => "topic",
                "key" => "logger.level.fatal"
            ],
            "warning" => [
                "name" => "warning",
                "exchange_name" => "logger.topic",
                "exchange" => "topic",
                "key" => "logger.level.warning"
            ],
            "any" => [
                "name" => "any",
                "exchange_name" => "logger.fanout",
                "exchange" => "fanout",
                "key" => false,
                "queues" => [
                    "debug",
                    "warning"
                ]
            ]
        ]
    ];
    public const FILE_NAME = "test.log";
    public function getConfig(): array|null;
}