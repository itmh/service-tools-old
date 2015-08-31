<?php

/**
 * ConfigurationException
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

use Exception;

/**
 * Исключение, выбрасываемое при попытке вызвать метод у класса,
 * реализующего ConfigurableInterface, который не был сконфигурирован
 * (не вызван метод ConfigurableInterface->configure)
 *
 * @category Core
 * @package  ServiceTools\Core\Exceptions
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class NotConfiguredException extends Exception
{
    /**
     * Конструктор исключения, с именем класса несконфигурированного компонента
     * @param string $implName название класса компонента
     */
    public function __construct($implName)
    {
        $message = sprintf('%s is not configured, use ->configure first', $implName);
        parent::__construct($message, 0, null);
    }
}
