<?php

/**
 * MonologLogger
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core\Loggers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Core\Loggers;

use Monolog\Handler\HandlerInterface;
use Monolog\Logger;
use ServiceTools\Core\Exceptions\ConfigurationError;

/**
 * Реализация интерфейса логгера на основе monolog/monolog
 * https://github.com/Seldaek/monolog
 *
 * @category Core
 * @package  ServiceTools\Core\Loggers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class MonologLogger extends AbstractLogger
{
    const ERR__LOGGER_NAME = 'Logger name is not specified';
    const ERR__LOGGER_HANDLER_CLASS = 'Logger handler class is not specified';

    /**
     * экземпляр логгера
     * @var Logger
     */
    private $impl;

    /**
     * минимальный уровень логгирования
     * @var int
     */
    private $severity;

    /**
     * Производит конфигурирование компонента
     *
     * @param array $config опции конфигурации
     *
     * @return $this
     * @throws ConfigurationError
     */
    public function configure(array $config = [])
    {
        if (!isset($config['name'])) {
            throw new ConfigurationError(self::ERR__LOGGER_NAME);
        }
        if (!isset($config['severity'])) {
            $config['severity'] = 'NOTICE';
        }
        if (!isset($config['handler']['class'])) {
            throw new ConfigurationError(self::ERR__LOGGER_HANDLER_CLASS);
        }
        if (!isset($config['handler']['name'])) {
            $config['handler']['name'] = 'ServiceTools';
        }

        $severity = sprintf(
            'ServiceTools\Core\Loggers\AbstractLogger::%s',
            $config['severity']
        );
        $severity = defined($severity) ? constant($severity) : AbstractLogger::NOTICE;
        $this->severity = $severity;
        $this->impl = new Logger($config['name']);

        /* @var HandlerInterface $handler */
        $handler = new $config['handler']['class']($config['handler']['name']);
        $this->impl->pushHandler($handler);

        return parent::configure($config);
    }

    /**
     * Возвращает массив с конфигурацией по умолчанию
     *
     * @param array $config массив с переопределениями конфигурации по умолчанию
     *
     * @return array
     */
    public static function getDefaultConfig(array $config = [])
    {
        $defaults = [
            'class' => 'ServiceTools\Core\Loggers\MonologLogger',
            'options' => [
                'name' => 'service-tools',
                'handler' => [
                    'class' => 'Monolog\Handler\SyslogHandler',
                    'options' => [
                        'name' => 'ServiceTools'
                    ]
                ],
                'severity' => 'WARNING'
            ]
        ];

        return array_replace_recursive($defaults, $config);
    }

    /**
     * Отправляет сообщение в лог
     *
     * @param string $message сообщение
     * @param int    $level   уровень
     *
     * @return void
     */
    public function log($message, $level = AbstractLogger::NOTICE)
    {
        if ($level < $this->severity) {
            return;
        }

        switch ($level) {
            case AbstractLogger::EMERGENCY:
                $this->impl->addEmergency($message);
                break;
            case AbstractLogger::ALERT:
                $this->impl->addAlert($message);
                break;
            case AbstractLogger::CRITICAL:
                $this->impl->addCritical($message);
                break;
            case AbstractLogger::ERROR:
                $this->impl->addError($message);
                break;
            case AbstractLogger::WARNING:
                $this->impl->addWarning($message);
                break;
            case AbstractLogger::NOTICE:
                $this->impl->addNotice($message);
                break;
            case AbstractLogger::INFO:
                $this->impl->addInfo($message);
                break;
            case AbstractLogger::DEBUG:
                $this->impl->addDebug($message);
                break;
        }
    }
}
