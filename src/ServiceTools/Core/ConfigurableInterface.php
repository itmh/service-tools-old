<?php

/**
 * ConfigurableInterface
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

/**
 * Интерфейс, добавляющий метод конфигурации сервиса перед использованием,
 * а также предоставляющим возможность узнать состояние конфигурации компонента,
 * и получить массив с конфигурацией по умолчанию
 *
 * @category Core
 * @package  ServiceTools\Core
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
interface ConfigurableInterface
{
    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return ConfigurableInterface
     */
    public function configure(array $config = []);

    /**
     * Возвращает состояние конфигурации
     * @return bool
     */
    public function isConfigured();

    /**
     * Возвращает массив с конфигурацией по умолчанию
     * @param array $config массив с переопределениями конфигурации по умолчанию
     * @return array
     */
    public static function getDefaultConfig(array $config = []);
}
