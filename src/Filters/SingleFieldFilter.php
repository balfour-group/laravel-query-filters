<?php

namespace Balfour\LaravelQueryFilters\Filters;

abstract class SingleFieldFilter extends BaseFilter
{
    /**
     * @var string
     */
    protected $field;

    /**
     * @param string $key
     * @param string|null $field
     * @param mixed $default
     */
    public function __construct(
        $key,
        $field = null,
        $default = null
    ) {
        $this->field = $field ?? $key;

        parent::__construct($key, $default);
    }

    /**
     * @return string
     */
    public function getField()
    {
        return $this->field;
    }
}
