<?php

namespace Balfour\LaravelQueryFilters\Filters;

class DateLessThanFilter extends SingleFieldFilter
{
    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        $query->whereDate($this->field, '<', $value);
    }
}
