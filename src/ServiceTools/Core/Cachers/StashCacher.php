<?php

/**
 * StashCacher
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core\Cachers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Core\Cachers;

use ServiceTools\Core\Exceptions\ConfigurationError;
use Stash\Interfaces\DriverInterface;
use Stash\Pool;

/**
 * Реализация интерфейса кэшера на основе tedivm/stash
 * https://github.com/tedious/stash
 *
 * @category Core
 * @package  ServiceTools\Core\Cachers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class StashCacher extends AbstractCacher
{
    const ERR__CACHER_DRIVER_CLASS = 'Cacher driver class is not specified';
    const ERR__CACHER_DRIVER_OPTIONS = 'Cacher driver options is not specified';

    /**
     * экземпляр кэшера
     * @var Pool
     */
    private $impl;

    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return StashCacher
     * @throws ConfigurationError
     */
    public function configure(array $config = [])
    {
        if (!isset($config['driver']['class'])) {
            throw new ConfigurationError(self::ERR__CACHER_DRIVER_CLASS);
        }
        if (!isset($config['driver']['options'])) {
            throw new ConfigurationError(self::ERR__CACHER_DRIVER_OPTIONS);
        }

        /* @var DriverInterface $driver */
        $driver = new $config['driver']['class']();
        $driver->setOptions($config['driver']['options']);

        $this->impl = new Pool($driver);

        return parent::configure($config);
    }

    /**
     * Возвращает массив с конфигурацией по умолчанию
     * @param array $config массив с переопределениями конфигурации по умолчанию
     * @return array
     */
    public static function getDefaultConfig(array $config = [])
    {
        $defaults = [
            'class' => 'ServiceTools\Core\Cachers\StashCacher',
            'options' => [
                'driver' => [
                    'class' => 'Stash\Driver\FileSystem',
                    'options' => [
                        'path' => '/tmp/stash'
                    ]
                ]
            ],
            'expires' => [
                'default' => 0
            ]
        ];

        return array_replace_recursive($defaults, $config);
    }

    /**
     * Проверяет состояние данных
     * @param string $key ключ
     * @return bool
     */
    public function isExpired($key)
    {
        return $this->impl->getItem($key)->isMiss();
    }

    /**
     * Получает данные из кэша
     * @param string $key ключ
     * @return mixed
     */
    public function get($key)
    {
        return $this->impl->getItem($key)->get();
    }

    /**
     * Помещает данные в кэш
     * @param string $key     ключ
     * @param mixed  $value   значение
     * @param int    $expires время жизни, сек
     * @return mixed
     */
    public function set($key, $value, $expires = 0)
    {
        $this->impl->getItem($key)->set($value, $expires);
    }
}
