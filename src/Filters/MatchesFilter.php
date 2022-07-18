<?php

namespace Balfour\LaravelQueryFilters\Filters;

class MatchesFilter extends SingleFieldFilter
{
    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        if ($value === 'null') {
            $query->whereNull($this->field);
        } else {
            $query->where($this->field, $value);
        }
    }
}
