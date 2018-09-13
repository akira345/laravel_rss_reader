<?php

namespace App\Logging;

use Monolog\Logger;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Handler\FingersCrossedHandler;
use Monolog\Formatter\LineFormatter;
use Monolog\Processor\WebProcessor;
use Monolog\Processor\IntrospectionProcessor;

class RssSendLog extends LogDriverAbstract
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config config/logging.php で指定した rss_send_log 以下のものを取得できる
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        // StreamHandler を生成
        $handler = $this->prepareHandler(
            new RotatingFileHandler(
                $config['path'], $config['days'] ?? 7, $this->level($config),
                $config['bubble'] ?? true, $config['permission'] ?? null, $config['locking'] ?? false
            )
        );

        // ログに出力するフォーマット
        $format = '[%datetime% %channel%.%level_name%] %message% [%context%]' . PHP_EOL;

        // StreamHandler にフォーマッタをセット
        $handler->setFormatter(
            tap(new LineFormatter($format, null, true, true), function (\Monolog\Formatter\LineFormatter $formatter) {
                $formatter->includeStacktraces();
            })
        );

        // Monolog のインスタンスを生成して返す
        return new Logger($this->parseChannel($config), [
            new FingersCrossedHandler(
                $handler,
                $config['activation'] ?? null,
                0,
                true,
                true,
                $config['pass'] ?? null
            )
        ]);
    }
}