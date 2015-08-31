<?php

/**
 * SasService
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
 * Реализация взаимодействия с Единым Сервисом Авторизации
 *
 * @method Response PingAPI()
 * @method Response AuthUserByLogin(string $login, string $password)
 *
 * @category Services
 * @package  ServiceTools\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
final class SasService extends AbstractSoapService
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
            'defaultArgs' => []
        ];

        return array_replace_recursive($defaults, $config);
    }
}
