<?php

/**
 * AbstractHttpService
 *
 * PHP Version 5
 *
 * @category Core
 * @package  ServiceTools\Core\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Core\Services;

use GuzzleHttp\Client;
use ServiceTools\Core\Exceptions\ConfigurationError;

/**
 * Абстрактный сервис, делающий HTTP запросы
 *
 * @category Core
 * @package  ServiceTools\Core\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractHttpService extends AbstractService
{
    const ERR__URL = 'Url is not specified';

    const DEFAULT_TIMEOUT = 5;

    /**
     * экземпляр http клиента
     * @var Client
     */
    private $impl;

    /**
     * Производит конфигурирование сервиса
     * @param array $config опции конфигурации
     * @return AbstractHttpService
     * @throws ConfigurationError
     */
    public function configure(array $config = [])
    {
        if (!isset($config['url'])) {
            throw new ConfigurationError(self::ERR__URL);
        }

        $this->impl = new Client();

        return parent::configure($config);
    }

    /**
     * Выполняет get запрос
     * @param string $url  адрес
     * @param array  $args параметры
     * @return mixed|null
     */
    protected function httpGet($url, array $args = [])
    {
        return $this->impl->get($url, $args);
    }

    /**
     * Выполняет post запрос
     * @param string $url  адрес
     * @param array  $args параметры
     * @return mixed|null
     */
    protected function httpPost($url, array $args = [])
    {
        return $this->impl->post($url, $args);
    }
}
