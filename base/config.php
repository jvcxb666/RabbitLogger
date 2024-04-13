<?php

return [
    "rabbitConnection" => ['host' => 'rabbitmq'],
    "baseLogger" => [
        "queues" => [
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
        ],
        "log_filename" => "test.log",
    ],
    "curlLogger" => [
        "queues" => [
            "curl" => [
                "name" => "curl",
                "exchange_name" => "curl.topic",
                "exchange" => "topic",
                "key" => "logger.curl.main"
            ],
        ]
    ],
];