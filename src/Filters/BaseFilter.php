<?php

namespace Balfour\LaravelQueryFilters\Filters;

use Balfour\LaravelQueryFilters\FilterSet;

abstract class BaseFilter implements FilterInterface
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var mixed|null
     */
    protected $default;

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $key
     * @param mixed $default
     */
    public function __construct($key, $default = null)
    {
        $this->key = $key;
        $this->default = $default;
    }

    /**
     * @return mixed|null
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return is_callable($this->default) ? call_user_func($this->default) : $this->default;
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getValue(array $params)
    {
        if (isset($params[$this->key])) {
            $value = $params[$this->key];

            if (!FilterSet::isEmptyValue($value)) {
                return $value;
            }
        }

        return $this->getDefaultValue();
    }
}
