<?php

namespace App\Logging;

use Monolog\Logger;

class LoginLog extends LogDriverAbstract
{
    /**
     * Create a custom Monolog instance.
     *
     * @param  array  $config config/logging.php で指定した fingers 以下のものを取得できる
     * @return \Monolog\Logger
     */
    public function __invoke(array $config)
    {
        // StreamHandler を生成
        $handler = $this->prepareHandler(
            new StreamHandler($config['path'], $this->level($config))
        );

        // ログに出力するフォーマット
        $format = '[%datetime% %channel%.%level_name%] %message% [%context%] [%extra%]';

        // StreamHandler にフォーマッタをセット
        $handler->setFormatter(
            tap(new LineFormatter($format, null, true, true), function ($formatter) {
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