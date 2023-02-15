<?php

namespace App\Logging;

use Illuminate\Support\Str;
use InvalidArgumentException;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\FormattableHandlerInterface;
use Psr\Log\LoggerInterface;
use Monolog\Level;

abstract class LogDriverAbstract
{
    /**
     * The Log levels.
     *
     * @var array
     */
    protected $levels = [
        'debug'     => Level::Debug,
        'info'      => Level::Info,
        'notice'    => Level::Notice,
        'warning'   => Level::Warning,
        'error'     => Level::Error,
        'critical'  => Level::Critical,
        'alert'     => Level::Alert,
        'emergency' => Level::Emergency,
    ];

    /**
     * Apply the configured taps for the logger.
     *
     * @param array                    $config
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return \Psr\Log\LoggerInterface
     */
    protected function tap(array $config, LoggerInterface $logger)
    {
        foreach ($config['tap'] ?? [] as $tap) {
            list($class, $arguments) = $this->parseTap($tap);

            app()->make($class)->__invoke($logger, ...explode(',', $arguments));
        }

        return $logger;
    }

    /**
     * Parse the given tap class string into a class name and arguments string.
     *
     * @param string $tap
     *
     * @return array
     */
    protected function parseTap($tap)
    {
        return Str::contains($tap, ':') ? explode(':', $tap, 2) : [$tap, ''];
    }

    /**
     * Prepare the handler for usage by Monolog.
     *
     * @param \Monolog\Handler\FormattableHandlerInterface $handler
     *
     * @return \Monolog\Handler\FormattableHandlerInterface
     */
    protected function prepareHandler(FormattableHandlerInterface $handler)
    {
        return $handler->setFormatter($this->formatter());
    }

    /**
     * Get a Monolog formatter instance.
     *
     * @return \Monolog\Formatter\FormatterInterface
     */
    protected function formatter()
    {
        return tap(new LineFormatter(null, null, true, true), function ($formatter) {
            $formatter->includeStacktraces();
        });
    }

    /**
     * Extract the log channel from the given configuration.
     *
     * @param array $config
     *
     * @return string
     */
    protected function parseChannel(array $config)
    {
        if (!isset($config['name'])) {
            return app()->bound('env') ? app()->environment() : 'production';
        }

        return $config['name'];
    }

    /**
     * Parse the string level into a Monolog constant.
     *
     * @param array $config
     *
     * @return int
     *
     * @throws \InvalidArgumentException
     */
    protected function level(array $config)
    {
        $level = $config['level'] ?? 'debug';

        if (isset($this->levels[$level])) {
            return $this->levels[$level];
        }

        throw new InvalidArgumentException('Invalid log level.');
    }
}
