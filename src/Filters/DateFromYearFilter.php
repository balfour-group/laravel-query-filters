<?php

namespace Balfour\LaravelQueryFilters\Filters;

use Balfour\LaravelQueryFilters\FilterSet;

class DateFromYearFilter extends SingleFieldFilter
{
    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        $query->whereBetween($this->field, $this->buildValues($value));
    }

    /**
     * @param string $value
     * @return array
     */
    public function buildValues(string $value): array
    {
        return [
            $value . '-01-01',
            $value . '-12-31'
        ];
    }
}
