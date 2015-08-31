<?php

/**
 * Pinba
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Helpers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Helpers;

/**
 * Обёртка для проверки и вызова функций замерения времени выполнения участков кода
 * https://github.com/tony2001/pinba_engine/wiki/PHP-extension
 *
 * @category Core
 * @package  ServiceTools\Helpers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
final class Pinba
{
    /**
     * Проверяет доступность расширения и флаг включения
     * @return bool
     */
    private function isEnabled()
    {
        return extension_loaded('pinba') && ini_get('pinba.enabled');
    }

    /**
     * Запускает таймер и возвращает ресурс
     * https://github.com/tony2001/pinba_engine/wiki/PHP-extension#pinba_timer_start
     * @param string $type
     * @param string $target
     * @param array  $more
     * @return resource|null
     */
    public static function start($type, $target, array $more = [])
    {
        if (!self::isEnabled()) {
            return null;
        }

        /** @noinspection PhpUndefinedFunctionInspection */

        return pinba_timer_start(array_merge(['type' => $type, 'target' => $target], $more));
    }

    /**
     * Останавливает таймер
     * https://github.com/tony2001/pinba_engine/wiki/PHP-extension#pinba_timers_stop
     * @param resource $resource
     * @return bool|null
     */
    public static function stop($resource)
    {
        if (!self::isEnabled()) {
            return null;
        }

        /** @noinspection PhpUndefinedFunctionInspection */

        return pinba_timer_stop($resource);
    }
}
