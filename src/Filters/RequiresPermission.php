<?php

namespace Balfour\LaravelQueryFilters\Filters;

class RequiresPermission implements FilterInterface
{
    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * @var string
     */
    protected $permission;

    /**
     * @var mixed|null
     */
    protected $defaultNoPermissionValue;

    /**
     * @var string
     */
    protected $guard;

    /**
     * @param FilterInterface $filter
     * @param string $permission
     * @param mixed $defaultNoPermissionValue
     * @param string $guard
     */
    public function __construct(FilterInterface $filter, $permission, $defaultNoPermissionValue = null, $guard = 'web')
    {
        $this->filter = $filter;
        $this->permission = $permission;
        $this->defaultNoPermissionValue = $defaultNoPermissionValue;
        $this->guard = $guard;
    }

    /**
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * @return string
     */
    public function getPermission()
    {
        return $this->permission;
    }

    /**
     * @return mixed|null
     */
    public function getDefaultNoPermissionValue()
    {
        return $this->defaultNoPermissionValue;
    }

    /**
     * @return mixed
     */
    public function getResolvedDefaultNoPermissionValue()
    {
        return is_callable($this->defaultNoPermissionValue) ?
            call_user_func($this->defaultNoPermissionValue) :
            $this->defaultNoPermissionValue;
    }

    /**
     * @return string
     */
    public function getGuard()
    {
        return $this->guard;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->filter->getKey();
    }

    /**
     * @return mixed
     */
    public function getDefaultValue()
    {
        return $this->isAuthorized() ?
            $this->filter->getDefaultValue() :
            $this->getResolvedDefaultNoPermissionValue();
    }

    /**
     * @param array $params
     * @return mixed
     */
    public function getValue(array $params)
    {
        return $this->isAuthorized() ?
            $this->filter->getValue($params) :
            $this->getDefaultValue();
    }

    /**
     * @return bool
     */
    protected function isAuthorized()
    {
        $guard = auth($this->guard);

        if ($guard->check()) {
            $user = $guard->user();

            if ($user->can($this->permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param mixed $query
     * @param mixed $value
     */
    public function apply($query, $value)
    {
        if ($this->isAuthorized()) {
            $this->filter->apply($query, $value);
        } else {
            $value = $this->getDefaultValue();
            if ($value) {
                $this->filter->apply($query, $value);
            }
        }
    }
}
