<?php

namespace Balfour\LaravelQueryFilters;

use Balfour\LaravelQueryFilters\Filters\FilterInterface;
use Exception;
use Illuminate\Support\Collection;
use UnexpectedValueException;

class FilterSet
{
    /**
     * @var Collection
     */
    protected $filters;

    /**
     * @var Collection
     */
    protected $sorts;

    /**
     * @var Sort|callable|null
     */
    protected $defaultSort;

    /**
     * @var callable|null
     */
    protected $queryCallback;

    /**
     * @param array $filters
     * @param array $sorts
     * @param Sort|callable|string|null $defaultSort
     * @param callable|null $queryCallback
     * @throws Exception
     */
    public function __construct(
        array $filters = [],
        array $sorts = [],
        $defaultSort = null,
        callable $queryCallback = null
    ) {
        $this->filters = collect();
        $this->sorts = collect();
        $this->defaultSort = $defaultSort;
        $this->queryCallback = $queryCallback;

        $this->addFilters($filters);
        $this->addSorts($sorts);
    }

    /**
     * @param FilterInterface $filter
     * @throws Exception
     */
    public function addFilter(FilterInterface $filter)
    {
        $key = $filter->getKey();

        if ($this->filters->has($key)) {
            throw new Exception(sprintf('The filter "%s" already exists.', $key));
        }

        $this->filters->put($key, $filter);
    }

    /**
     * @param array $filters
     * @throws Exception
     */
    public function addFilters(array $filters)
    {
        foreach ($filters as $filter) {
            /** @var FilterInterface $filter */
            $this->addFilter($filter);
        }
    }

    /**
     * @return Collection
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param Sort $sort
     * @throws Exception
     */
    public function addSort(Sort $sort)
    {
        $key = $sort->getKey();

        if ($this->sorts->has($key)) {
            throw new Exception(sprintf('The sort "%s" already exists.', $key));
        }

        $this->sorts->put($key, $sort);
    }

    /**
     * @param array $sorts
     * @throws Exception
     */
    public function addSorts(array $sorts)
    {
        foreach ($sorts as $sort) {
            /** @var Sort $sort */
            $this->addSort($sort);
        }
    }

    /**
     * @return Collection
     */
    public function getSorts()
    {
        return $this->sorts;
    }

    /**
     * @param string $key
     * @return Sort
     */
    public function getSort($key)
    {
        if (!$this->isSort($key)) {
            throw new UnexpectedValueException(sprintf('The sort "%s" is not available.', $key));
        }

        return $this->sorts->get($key);
    }

    /**
     * @param string $key
     * @return bool
     */
    public function isSort($key)
    {
        return $this->sorts->has($key);
    }

    /**
     * @param Sort|callable|string|null $sort
     */
    public function setDefaultSort($sort)
    {
        $this->defaultSort = $sort;
    }

    /**
     * @return Sort|callable|string|null
     */
    public function getDefaultSort()
    {
        return $this->defaultSort;
    }

    /**
     * @return Sort|null
     */
    public function getResolvedDefaultSort()
    {
        if ($this->defaultSort instanceof Sort) {
            return $this->defaultSort;
        } elseif (is_string($this->defaultSort)) {
            return $this->getSort($this->defaultSort);
        } elseif (is_callable($this->defaultSort)) {
            return call_user_func($this->defaultSort);
        } else {
            return null;
        }
    }

    /**
     * @param callable|null $queryCallback
     */
    public function setQueryCallback($queryCallback)
    {
        $this->queryCallback = $queryCallback;
    }

    /**
     * @return callable|null
     */
    public function getQueryCallback()
    {
        return $this->queryCallback;
    }

    /**
     * @param mixed $query
     * @param array $values
     */
    public function apply($query, array $values)
    {
        // we first apply filters
        foreach ($this->filters as $filter) {
            /** @var FilterInterface $filter */
            // if we don't have a value, or the value given is an empty value, use default
            $value = $filter->getValue($values);

            // if empty value, then there's nothing to apply on this filter
            if (static::isEmptyValue($value)) {
                continue;
            }

            $filter->apply($query, $value);
        }

        // next, we apply a sort
        $sort = isset($values['sort']) && $this->isSort($values['sort']) ?
            $this->getSort($values['sort']) :
            $this->getResolvedDefaultSort();

        $direction = $values['sort_dir'] ?? null;

        if ($direction !== 'asc' && $direction !== 'desc') {
            // invalid direction must use sort's default direction
            $direction = null;
        }

        if ($sort) {
            $sort->apply($query, $direction);
        }

        // finally, if a query callback is specified, we invoke it now
        if ($this->queryCallback) {
            call_user_func($this->queryCallback, $query);
        }
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public static function isEmptyValue($value)
    {
        if ($value === null) {
            return true;
        }

        if (is_bool($value)) {
            return false;
        }

        return trim((string) $value) === '';
    }
}
