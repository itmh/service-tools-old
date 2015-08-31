<?php

/**
 * SchemaException
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core\Exceptions
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Core\Exceptions;

use ErrorException;

/**
 * Исключение, выбрасываемое при некорректности схемы в базе данных
 *
 * @category Core
 * @package  ServiceTools\Core\Exceptions
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class SchemaException extends ErrorException
{

}
