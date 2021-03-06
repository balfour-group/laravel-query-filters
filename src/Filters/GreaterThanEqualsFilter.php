<?php

namespace Balfour\LaravelQueryFilters\Filters;

class GreaterThanEqualsFilter extends SingleFieldFilter
{
    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        $query->where($this->field, '>=', $value);
    }
}
