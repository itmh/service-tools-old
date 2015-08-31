<?php

/**
 * Response
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

use ErrorException;

/**
 * Класс-обёртка над возвращаемым результатом
 * Содержит флаг успешности, значение,
 * а также текст ошибки, если есть
 *
 * @category Core
 * @package  ServiceTools\Core\Services
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
class Response
{
    /**
     * флаг успешности запроса
     * @var bool
     */
    private $isSuccess = false;

    /**
     * тело ответа
     * @var mixed
     */
    private $content;

    /**
     * строка с описанием ошибки
     * @var string
     */
    private $error;

    /**
     * Конструктор экземпляра, используется только во вспомогательных методах
     * @param bool   $isSuccess флаг успешности
     * @param mixed  $body      тело ответа
     * @param string $error     строка с описанием ошибки
     * @throws ErrorException
     */
    private function __construct($isSuccess, $body, $error = null)
    {
        if (!$this->isSerializable($body)) {
            throw new ErrorException('Response body is not serializable');
        }
        $this->isSuccess = $isSuccess;
        $this->content = $body;
        $this->error = $error;
    }

    /**
     * Проверка на возможность сериализации тела ответа, для сохранения в кэше
     * @param mixed $body тело ответа
     * @return bool
     */
    private function isSerializable($body)
    {
        $return = true;
        $array = [$body];
        array_walk_recursive(
            $array,
            function ($e) use (&$return) {
                if (is_object($e) && get_class($e) === 'Closure') {
                    $return = false;
                }
            }
        );
        return $return;
    }

    /**
     * Хэлпер для возврата успешного результата
     * @param mixed $body тело ответа
     * @return Response
     */
    public static function success($body = null)
    {
        return new self(true, $body);
    }

    /**
     * Хэлпер для возврата неуспешного результата
     * @param mixed  $body  тело ответа
     * @param string $error строка с описанием ошибки
     * @return Response
     */
    public static function failure($body = null, $error = null)
    {
        return new self(false, $body, $error);
    }

    /**
     * Возвращает флаг успешности
     * @return bool
     */
    public function isOk()
    {
        return $this->isSuccess;
    }

    /**
     * Возвращает тело ответа
     * @return mixed
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Возвращает строку с описанием ошибки
     * @return string
     */
    public function getError()
    {
        return $this->error;
    }
}
