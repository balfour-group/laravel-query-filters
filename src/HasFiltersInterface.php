<?php

namespace Balfour\LaravelQueryFilters;

interface HasFiltersInterface
{
    /**
     * @return FilterSet
     */
    public function getFilterSet();
}
