<?php

/**
 * AbstractCacher
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

use ServiceTools\Core\ConfigurableInterface;

/**
 * Абстрактное описание кэшера, с минимальным набором методов
 *
 * @category Core
 * @package  ServiceTools\Core\Cachers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractCacher implements ConfigurableInterface
{
    /**
     * флаг выполнения конфигурации
     * @var bool
     */
    protected $configured;

    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return AbstractCacher
     */
    public function configure(array $config = [])
    {
        $this->configured = true;

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
     * Проверяет состояние данных
     * @param string $key ключ
     * @return bool
     */
    abstract public function isExpired($key);

    /**
     * Получает данные из кэша
     * @param string $key ключ
     * @return mixed
     */
    abstract public function get($key);

    /**
     * Помещает данные в кэш
     * @param string $key     ключ
     * @param mixed  $value   значение
     * @param int    $expires время жизни, сек
     * @return mixed
     */
    abstract public function set($key, $value, $expires = 0);
}
