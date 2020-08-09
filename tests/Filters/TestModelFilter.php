<?php

namespace Tests\Filters;

use LaravelEloquentFilter\BaseFilter;
use Illuminate\Database\Eloquent\Builder;

class TestModelFilter extends BaseFilter
{
    /**
     * Registered filters to operate upon.
     *
     * @var array
     */
    protected $filters = [
        'name',
    ];

    /**
     * @return Builder
     */
    protected function name($value)
    {
        return $this->builder->where('name', 'like', "%$value%");
    }

    /**
     * @return Builder
     */
    protected function defaultName()
    {
        return $this->builder->where('name', 'Mohammed');
    }
}