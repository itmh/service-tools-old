<?php

/**
 * RisService
 *
 * PHP Version 5
 *
 * @category Services
 * @package  ServiceTools\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Services;

use ServiceTools\Core\Services\AbstractSoapService;
use ServiceTools\Core\Services\Response;

/**
 * Реализация взаимодействия с РИС
 *
 * @method Response UserInfo(array $args) ['ntlogin' => '']
 *
 * @category Services
 * @package  ServiceTools\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class RisService extends AbstractSoapService
{
    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return SasService
     */
    public function configure(array $config = [])
    {
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
            'url' => '',
            'login' => '',
            'password' => ''
        ];

        return array_replace_recursive($defaults, $config);
    }
}
