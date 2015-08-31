<?php

/**
 * AbstractSoapService
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

use Camcima\Soap\Client;
use ServiceTools\Core\Exceptions\ConfigurationError;
use ServiceTools\Core\Loggers\AbstractLogger;
use SoapFault;

/**
 * Абстрактный сервис, делающий SOAP запросы
 *
 * @category Core
 * @package  ServiceTools\Core\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractSoapService extends AbstractService
{
    const ERR__URL = 'Application url is not specified';
    /**
     * экземпляр soap клиента
     * @var Client
     */
    private $impl;

    /**
     * аргументы, передающиеся в каждый запрос
     * @var array
     */
    protected $defaultArgs = [];

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
        if (isset($config['defaultArgs'])) {
            $this->defaultArgs = $config['defaultArgs'];
        }

        $this->impl = new Client(
            $this->getSoapUrl($config),
            $this->getSoapParams($config)
        );

        if (isset($config['curlClientParams'])) {
            $this->impl->setCurlOptions($config['curlClientParams']);
        }

        return parent::configure($config);
    }

    /**
     * Возвращает адрес soap сервера
     * @param array $config опции конфигурации
     * @return string
     */
    public function getSoapUrl(array $config = [])
    {
        return $config['url'];
    }

    /**
     * Возвращает параметры для инициализации клиента
     * @param array $config опции конфигурации
     * @return array
     */
    public function getSoapParams(array $config = [])
    {
        $params = [
            'encoding' => 'UTF-8'
        ];
        if (isset($config['soapClientParams'])) {
            $params = array_merge($params, $config['soapClientParams']);
        }

        return $params;
    }

    /**
     * Возвращает результат сервисного взаимодействия
     * @param string $method имя метода
     * @param array  $args   аргументы
     * @return Response
     */
    protected function implementationCall($method, array $args = [])
    {
        try {
            $args = array_merge($this->defaultArgs, $args);
            $result = $this->impl->__soapCall($method, $args);
            if (isset($this->config['__map'][$method])) {
                $result = $this->impl->mapSoapResult(
                    $result,
                    $method,
                    $this->config['__map']
                );
            }

            return Response::success($result);
        } catch (SoapFault $e) {
            $message = $e->getMessage();
            $this->loggerNotice($message, AbstractLogger::ERROR);

            return Response::failure(null, $message);
        }
    }
}
