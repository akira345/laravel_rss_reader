<?php

use Monolog\Handler\StreamHandler;

return [

    /*
    |--------------------------------------------------------------------------
    | Default Log Channel
    |--------------------------------------------------------------------------
    |
    | This option defines the default log channel that gets used when writing
    | messages to the logs. The name specified in this option should match
    | one of the channels defined in the "channels" configuration array.
    |
    */

    'default' => env('LOG_CHANNEL', 'stack'),

    /*
    |--------------------------------------------------------------------------
    | Log Channels
    |--------------------------------------------------------------------------
    |
    | Here you may configure the log channels for your application. Out of
    | the box, Laravel uses the Monolog PHP logging library. This gives
    | you a variety of powerful log handlers / formatters to utilize.
    |
    | Available Drivers: "single", "daily", "slack", "syslog",
    |                    "errorlog", "monolog",
    |                    "custom", "stack"
    |
    */

    'channels' => [
        'stack' => [
            'driver' => 'stack',
            'channels' => ['daily'],
        ],

        'single' => [
            'driver' => 'single',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
        ],

        'daily' => [
            'driver' => 'daily',
            'path' => storage_path('logs/laravel.log'),
            'level' => 'debug',
            'days' => 30,
        ],

        'slack' => [
            'driver' => 'slack',
            'url' => env('LOG_SLACK_WEBHOOK_URL'),
            'username' => 'Laravel Log',
            'emoji' => ':boom:',
            'level' => 'critical',
        ],

        'stderr' => [
            'driver' => 'monolog',
            'handler' => StreamHandler::class,
            'with' => [
                'stream' => 'php://stderr',
            ],
        ],

        'syslog' => [
            'driver' => 'syslog',
            'level' => 'debug',
        ],

        'errorlog' => [
            'driver' => 'errorlog',
            'level' => 'debug',
        ],

        'authlog' => [
            'driver' => 'custom',
            'via' => \App\Logging\AuthLog::class,
            'path' => storage_path('logs/auth.log'),
            'level'      => 'debug', // 指定したハンドラで出力するログレベル
            'activation' => 'error', // このログレベル以上で指定したハンドラで出力するレベルのログを出力する
            'pass'       => 'info', // このログレベル以上は常に出力する
            'days' => 30,  //保存日数
        ],
        'rss_send_log' => [
            'driver' => 'custom',
            'via' => \App\Logging\RssSendLog::class,
            'path' => storage_path('logs/rss_send_log.log'),
            'level'      => 'debug', // 指定したハンドラで出力するログレベル
            'activation' => 'error', // このログレベル以上で指定したハンドラで出力するレベルのログを出力する
            'pass'       => 'info', // このログレベル以上は常に出力する
            'days' => 30,  //保存日数
        ]
    ],

];
