<?php

namespace Balfour\LaravelQueryFilters\Filters;

interface FilterInterface
{
    /**
     * @return string
     */
    public function getKey();

    /**
     * @return mixed
     */
    public function getDefaultValue();

    /**
     * @param array $params
     * @return mixed
     */
    public function getValue(array $params);

    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value);
}
