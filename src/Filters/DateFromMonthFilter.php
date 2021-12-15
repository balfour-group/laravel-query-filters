<?php

namespace Balfour\LaravelQueryFilters\Filters;

use Balfour\LaravelQueryFilters\FilterSet;
use Carbon\Carbon;

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
        $splitDate = explode('-', $value);
        $date = Carbon::createFromDate( $splitDate[1], $splitDate[0]);

        return [
            $date->startOfMonth()->format('Y-m-d'),
            $date->endOfMonth()->format('Y-m-d')
        ];
    }
}
