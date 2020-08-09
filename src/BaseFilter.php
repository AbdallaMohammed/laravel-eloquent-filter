<?php

namespace LaravelEloquentFilter;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

abstract class BaseFilter
{
    /**
     * @var array
     */
    protected $values;

    /**
     * The Eloquent builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $builder;

    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [];

    /**
     * Create a new BaseFilters instance.
     *
     * @param \Illuminate\Http\Request|array $request
     */
    public function __construct($request)
    {
        if ($request instanceof Request) {
            $this->values = $request->query();
        } elseif (is_array($request)) {
            $this->values = $request;
        }
    }

    /**
     * Apply the filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $builder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function apply($builder)
    {
        $this->builder = $builder;
        foreach ($this->getFilters() as $filter) {
            $value = isset($this->values[$filter]) ? $this->values[$filter] : '';

            if (Config::get('laravel-eloquent-filter.ignore_empty', false) && empty($value)) {
                continue;
            }

            if (array_key_exists($filter, $this->values)) {
                $methodName = Str::camel($filter);
            } else {
                $methodName = 'default' . Str::studly($filter);
            }

            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }

        return $this->builder;
    }

    /**
     * Fetch all relevant filters from the request.
     *
     * @return array
     */
    public function getFilters()
    {
        return property_exists($this, 'filters')
        && is_array($this->filters) ? $this->filters : [];
    }
}
