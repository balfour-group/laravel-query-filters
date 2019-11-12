<?php

namespace Balfour\LaravelQueryFilters\Filters;

class CallableFilter extends BaseFilter
{
    /**
     * @var callable
     */
    protected $callable;

    /**
     * @param string $key
     * @param callable $callable
     * @param mixed $default
     */
    public function __construct($key, callable $callable, $default = null)
    {
        parent::__construct($key, $default);

        $this->callable = $callable;
    }

    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        call_user_func($this->callable, $query, $value);
    }
}
