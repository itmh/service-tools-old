<?php

/**
 * AbstractComponent
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Core;

use ServiceTools\Core\Cachers\AbstractCacher;
use ServiceTools\Core\Exceptions\ConfigurationError;
use ServiceTools\Core\Loggers\AbstractLogger;

/**
 * Абстрактный компонент, реализующий возможность инстанциирования
 * и конфигурирования кэшера и логгера
 *
 * @category Core
 * @package  ServiceTools\Core\Managers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractComponent implements ConfigurableInterface
{
    const ERR__CACHER_CLASS = 'Cacher class is not specified';
    const ERR__CACHER_OPTIONS = 'Cacher options is not specified';
    const ERR__LOGGER_CLASS = 'Logger class is not specified';
    const ERR__LOGGER_OPTIONS = 'Logger options is not specified';

    /**
     * массив опций конфигурации
     * @var array
     */
    protected $config = [];

    /**
     * флаг выполнения конфигурации
     * @var bool
     */
    private $configured = false;

    /**
     * реализация кэша
     * @var AbstractCacher
     */
    private $cacher;

    /**
     * карта для определения срока жизни результатов каждого метода (если указано)
     * @var array
     */
    private $expires = [];

    /**
     * реализация логгера
     * @var AbstractLogger
     */
    private $logger;

    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return AbstractComponent
     */
    public function configure(array $config = [])
    {
        $this->configureCacher($config);
        $this->configureLogger($config);

        $this->config = $config;
        $this->configured = true;
        $this->loggerNotice(sprintf('%s is configured', get_class($this)));

        return $this;
    }

    /**
     * Возвращает состояние конфигурации
     * @return bool
     */
    public function isConfigured()
    {
        return $this->configured;
    }

    /**
     * Конфигурирует экземпляр кэшера
     * @param array $config опции конфигурации
     * @return void
     * @throws ConfigurationError
     */
    private function configureCacher(array $config = [])
    {
        if (isset($config['__cacher'])) {
            $cacherOptions = $config['__cacher'];
            if (!isset($cacherOptions['class'])) {
                throw new ConfigurationError(self::ERR__CACHER_CLASS);
            }
            if (!isset($cacherOptions['options'])) {
                throw new ConfigurationError(self::ERR__CACHER_OPTIONS);
            }
            $this->cacher = new $cacherOptions['class']();
            $this->cacher->configure($cacherOptions['options']);

            if (isset($cacherOptions['expires'])) {
                $this->expires = $cacherOptions['expires'];
            }
        }
    }

    /**
     * Конфигурирует экземпляр логгера
     * @param array $config опции конфигурации
     * @return void
     * @throws ConfigurationError
     */
    private function configureLogger(array $config = [])
    {
        if (isset($config['__logger'])) {
            $loggerOptions = $config['__logger'];
            if (!isset($loggerOptions['class'])) {
                throw new ConfigurationError(self::ERR__LOGGER_CLASS);
            }
            if (!isset($loggerOptions['options'])) {
                throw new ConfigurationError(self::ERR__LOGGER_OPTIONS);
            }
            $this->logger = new $loggerOptions['class']();
            $this->logger->configure($loggerOptions['options']);
        }
    }

    /**
     * Возвращает экземпляр кэшера
     * @return AbstractCacher
     */
    public function getCacher()
    {
        return $this->cacher;
    }

    /**
     * Возвращает экземпляр логгера
     * @return AbstractLogger
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * Отправляет данные в лог, если экземпляр существует
     * @param string $message сообщение
     * @param string $tag     префикс
     * @return void
     */
    protected function loggerNotice($message, $tag = '')
    {
        if (isset($this->logger)) {
            $this->logger->log(
                ltrim(sprintf('%s %s', $tag, $message)),
                AbstractLogger::NOTICE
            );
        }
    }

    /**
     * Отправляет данные в лог, если экземпляр существует
     * @param string $message сообщение
     * @param string $tag     префикс
     * @return void
     */
    protected function loggerError($message, $tag = '')
    {
        if (isset($this->logger)) {
            $this->logger->log(
                ltrim(sprintf('%s %s', $tag, $message)),
                AbstractLogger::ERROR
            );
        }
    }

    /**
     * Возвращает время жизни результата вызова метода, если установлено
     * @param string $method имя метода
     * @return int
     */
    protected function cacherGetExpires($method)
    {
        if (isset($this->expires[$method])) {
            return $this->expires[$method];
        }
        if (isset($this->expires['default'])) {
            return $this->expires['default'];
        }

        return 0;
    }

    /**
     * Генерирует ключ-хэш для сохранения в кэше
     * @param string $method имя метода
     * @param array  $args   аргументы
     * @return string
     */
    protected function cacherGenerateKey($method, array $args)
    {
        return md5(serialize($method) . serialize($args));
    }

    /**
     * Проверяет состояние данных в кэше
     * @param string $cacheKey ключ
     * @return bool
     */
    protected function cacherIsExpired($cacheKey)
    {
        return !isset($this->cacher) || $this->cacher->isExpired($cacheKey);
    }

    /**
     * Получает данные из кэша
     * @param string $cacheKey ключ
     * @return mixed|null
     */
    protected function cacherGet($cacheKey)
    {
        if (isset($this->cacher)) {
            return $this->cacher->get($cacheKey);
        }

        return null;
    }

    /**
     * Помещает данные в кэш
     * @param string $cacheKey ключ
     * @param mixed  $result   значение
     * @param int    $expires  время жизни, сек
     * @return bool
     */
    protected function cacherSet($cacheKey, $result, $expires = 0)
    {
        if (isset($this->cacher)) {
            $this->cacher->set($cacheKey, $result, $expires);

            return true;
        }

        return false;
    }
}
