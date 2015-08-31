<?php

/**
 * ServiceToolsComponent
 *
 * PHP Version 5
 *
 * @category Yii
 * @package  ServiceTools\Yii
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */

namespace ServiceTools\Yii;

use ServiceTools\Core\Services\AbstractService;
use ServiceTools\Core\Services\Response;

/**
 * Класс для использования service-tools как компонента yii
 *
 * В секцию components настроек приложения добавляем нашу конфигурацию
 * <code>
 *   # имя компонента для использования в коде `Yii::$app->sasClient`
 *   'sasClient' => [
 *     # имя этого класса, для инициализации в Yii
 *     'class' => 'ServiceTools\Yii\ServiceComponent',
 *
 *     # имя сервиса, который будет предоставляться компонентом
 *     'service' => 'ServiceTools\Services\SasService',
 *
 *     # специфичные для конфигурации конкретного сервиса параметры
 *     # (см. метод ::getDefaultConfig() у соответствующего сервиса)
 *     'options' => [],
 *
 *     # общие параметры для кэшера и логгера
 *     # (см. метод ::getDefaultConfig у соответствующих логгера и кэшера)
 *     '__cacher' => [],
 *     '__logger' => [],
 *     ]
 *   ]
 * </code>
 *
 * Потом в нужном месте вызываем следующим образом
 * <code>
 * $sas = Yii::$app->get('sasClient');
 * $result = $sas->AuthUserByLogin('login', 'password');
 *
 * if ($result->isSuccess()) {
 *   echo print_r($result->getContent(), 1);
 * } else {
 *   echo $result->getError();
 * }
 * </code>
 *
 * @category Yii
 * @package  ServiceTools\Yii
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class ServiceToolsComponent
{
    /**
     * полное имя сервиса, вместе с пространством имён
     * @var string
     */
    public $serviceClass;

    /**
     * массив с настройками конкретного сервиса
     * @var array
     */
    public $options = [];

    /**
     * экземпляр конкретного сервиса
     * @var AbstractService
     */
    private $service;

    /**
     * Выполняет пробрасывание метода к экземпляру сервиса
     * @param string $method имя вызываемого метода
     * @param array  $args   аргументы
     * @return Response
     */
    public function __call($method, array $args = [])
    {
        if (!isset($this->service)) {
            $this->init();
        }

        return call_user_func_array([$this->service, $method], $args);
    }

    /**
     * Инициализирует компонент
     * @return void
     */
    private function init()
    {
        $this->service = new $this->serviceClass();
        $this->service->configure($this->options);
    }
}
