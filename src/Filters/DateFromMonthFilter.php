<?php

namespace Balfour\LaravelQueryFilters\Filters;

use Balfour\LaravelQueryFilters\FilterSet;

class DateFromMonthFilter extends SingleFieldFilter
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
        $date = \DateTime::createFromFormat('M Y', $value);

        return [
            $date->format('Y-m') . '-01',
            $date->format('Y-m') . '-31'
        ];
    }
}
