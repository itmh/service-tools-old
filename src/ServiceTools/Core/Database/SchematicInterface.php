<?php

/**
 * SchematicInterface
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core\Database
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Core\Database;

/**
 * SchematicInterface
 *
 * @category Core
 * @package  ServiceTools\Core\Database
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
interface SchematicInterface
{
    /**
     * Возвращает массив со списком молей в базе
     * @return array
     */
    public static function getSchema();
}
