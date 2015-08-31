<?php

/**
 * AbstractModel
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

use ErrorException;

/**
 * Абстрактная модель
 *
 * @category Core
 * @package  ServiceTools\Core\Database
 * @author   Chshanovskiy Maxim <chshanovskiy.maxim@itmh.ru>
 * @license  http://itmh.ru Proprietary
 * @link     http://itmh.ru
 */
abstract class AbstractModel implements SchematicInterface
{
    /**
     * Массив полей и значений объекта, считываемый через __get
     * @var array
     */
    protected $props;

    /**
     * Конструктор экземпляра
     * @param array $schema схема полей
     */
    public function __construct(array $schema = [])
    {
        $this->props = array_fill_keys($schema, null);
    }

    /**
     * Возвращает массив с данными модели
     * @param array $overrides
     * @return array
     */
    public function getValues(array $overrides = [])
    {
        return array_replace_recursive($this->props, $overrides);
    }

    /**
     * Возвращает значение поля, если существует
     * @param string $name имя поля
     * @return mixed
     * @throws ErrorException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->props)) {
            return $this->props[$name];
        }
        throw new ErrorException(sprintf('Property "%s" does not exists', $name));
    }

    /**
     * Бросает исключение, потому что модель только для чтения
     * @param string $name  имя поля
     * @param mixed  $value значение
     * @return void
     * @throws ErrorException
     */
    public function __set($name, $value)
    {
        throw new ErrorException(sprintf('Property "%s" is readonly', $name));
    }

    /**
     * Возвращает строковое представление модели
     * @return string
     */
    public function __toString()
    {
        return json_encode($this->props, JSON_UNESCAPED_UNICODE);
    }
}
