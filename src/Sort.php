<?php

namespace Balfour\LaravelQueryFilters;

class Sort
{
    /**
     * @var string
     */
    protected $key;

    /**
     * @var string|array
     */
    protected $field;

    /**
     * @var string
     */
    protected $defaultDirection;

    /**
     * @param string $key
     * @param string|array|null $field
     * @param string $defaultDirection
     */
    public function __construct($key, $field = null, $defaultDirection = 'asc')
    {
        $this->key = $key;
        $this->field = $field ?? $key;
        $this->defaultDirection = $defaultDirection;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string|array
     */
    public function getField()
    {
        return $this->field;
    }

    /**
     * @return string
     */
    public function getDefaultDirection()
    {
        return $this->defaultDirection;
    }

    /**
     * @param mixed $query
     * @param string $direction
     */
    public function apply($query, $direction = null)
    {
        $direction = $direction ?? $this->defaultDirection;

        $fields = !is_array($this->field) ? [$this->field] : $this->field;

        foreach ($fields as $field) {
            $query->orderBy($field, $direction);
        }
    }
}
