<?php

namespace Balfour\LaravelQueryFilters\Filters;

class DateGreaterThanEqualsFilter extends SingleFieldFilter
{
    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        $query->whereDate($this->field, '>=', $value);
    }
}
