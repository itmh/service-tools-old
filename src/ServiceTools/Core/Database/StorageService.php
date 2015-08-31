<?php

/**
 * StorageService
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

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use ServiceTools\Core\AbstractComponent;
use ServiceTools\Core\Exceptions\ConfigurationError;

/**
 * Сервис для реализации взаимодействия с базой данных
 *
 * @category Core
 * @package  ServiceTools\Core\Database
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class StorageService extends AbstractComponent
{
    /**
     * Соединение с базой данных
     * @var Connection
     */
    private $connection;

    /**
     * Производит конфигурирование компонента
     * @param array $config опции конфигурации
     * @return StorageService
     * @throws ConfigurationError
     */
    public function configure(array $config = [])
    {
        if (!isset($config['url'])) {
            throw new ConfigurationError('Url is not specified');
        }

        $this->connection = DriverManager::getConnection($config);

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
            'url' => 'driver://user:pass@host:port/dbname',
            'options' => []
        ];

        return array_replace_recursive($defaults, $config);
    }

    /**
     * Возвращает экземпляр подключения
     * @return Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }
}
