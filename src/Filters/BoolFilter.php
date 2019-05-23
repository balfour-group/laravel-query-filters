<?php

namespace Balfour\LaravelQueryFilters\Filters;

use Balfour\LaravelQueryFilters\FilterSet;

class BoolFilter extends SingleFieldFilter
{
    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        $query->where($this->field, $value);
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getValue(array $params)
    {
        if (isset($params[$this->key])) {
            $value = $params[$this->key];

            if (!FilterSet::isEmptyValue($value)) {
                return $this->castBool($value);
            }
        }

        return $this->getDefaultValue();
    }

    /**
     * @param mixed $value
     * @return bool
     */
    protected function castBool($value)
    {
        if (is_bool($value)) {
            return $value;
        }

        switch ($value) {
            case 'true':
            case 'yes':
            case 'on':
                return true;
            case 'false':
            case 'no':
            case 'off':
                return false;
        }

        return (bool) $value;
    }
}
