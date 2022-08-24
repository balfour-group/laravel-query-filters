<?php

namespace Balfour\LaravelQueryFilters\Filters;

class OrdersByCourseRunFilter extends SingleFieldFilter
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
            $query->leftJoin('delegate_information', 'order_items.id', '=', 'delegate_information.order_item_id')
                ->where('delegate_information.course_run_id', $value);
        }
    }
}
