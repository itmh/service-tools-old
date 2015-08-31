<?php

/**
 * AbstractService
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

use ServiceTools\Core\AbstractComponent;
use ServiceTools\Core\Exceptions\NotConfiguredException;
use ServiceTools\Core\Loggers\AbstractLogger;

/**
 * Абстрактный сервис, ответственный за проверку конфигурации и кэшированиe
 *
 * @category Core
 * @package  ServiceTools\Core\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractService extends AbstractComponent
{
    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return AbstractService
     */
    public function configure(array $config = [])
    {
        return parent::configure($config);
    }

    /**
     * Выполняет запрос, со всей инфрастуктурой - логгированием и кэшированием
     * Этот метод получает управление, когда у вызывается несуществующий метод
     * @param string $method имя вызываемого метода
     * @param array  $args   массив аргументов
     * @return Response
     * @throws NotConfiguredException
     */
    public function __call($method, array $args = [])
    {
        /* @var AbstractService $serviceName */
        $serviceName = get_class($this);
        if (!$this->isConfigured()) {
            $errorMessage = sprintf('%s is not configured', $serviceName);
            $this->loggerError($errorMessage);
            throw new NotConfiguredException($serviceName);
        }

        /* @var Response $result */
        $result = null;
        $tag = AbstractLogger::generateTag();
        $key = $this->cacherGenerateKey($method, $args);
        $expires = $this->cacherGetExpires($method);
        $isExpired = $this->cacherIsExpired($key);
        $msg = sprintf(
            'fetching result of "%1$s(%2$s)" from service %3$s::%1$s',
            $method,
            $this->varExport($args),
            $serviceName
        );
        $this->loggerNotice($msg, $tag);
        if (!$isExpired) {
            $this->loggerNotice('found in cache, retrieving', $tag);
            $result = $this->cacherGet($key);
        } else {
            $this->loggerNotice('not found in cache, fetching', $tag);
            $result = $this->implementationCall($method, $args);
            $this->loggerNotice(print_r($result->getContent(), true), $tag);
            if ($result->isOk()) {
                $this->loggerNotice('successfully fetched', $tag);
                $this->cacherSet($key, $result, $expires);
            } else {
                $this->loggerError($result->getError(), $tag);
            }
        }

        return $result;
    }

    /**
     * Возвращает строковое представление переменной для вывода в лог
     * @param mixed $mixed произвольная переменная
     * @return string
     */
    protected function varExport($mixed)
    {
        return json_encode($mixed, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Возвращает результат сервисного взаимодействия
     * @param string $method имя метода
     * @param array  $args   аргументы
     * @return Response
     */
    abstract protected function implementationCall($method, array $args = []);
}
