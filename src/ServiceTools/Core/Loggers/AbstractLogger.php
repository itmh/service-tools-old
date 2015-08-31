<?php

/**
 * AbstractLogger
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

use ServiceTools\Core\AbstractComponent;

/**
 * Абстрактный класс логгера, реализующий минимальную функциональность
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core\Loggers
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractLogger extends AbstractComponent
{
    const DEBUG = 100;
    const INFO = 200;
    const NOTICE = 250;
    const WARNING = 300;
    const ERROR = 400;
    const CRITICAL = 500;
    const ALERT = 550;
    const EMERGENCY = 600;

    /**
     * Генерирует префикс для группировки сообщений
     * @return string
     */
    public static function generateTag()
    {
        return substr(md5(uniqid()), 0, 8);
    }

    /**
     * Отправляет сообщение в логи
     *
     * @param string $message сообщение
     * @param int    $level   уровень
     *
     * @return void
     */
    abstract public function log($message, $level = self::NOTICE);
}
