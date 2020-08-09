<?php

namespace LaravelEloquentFilter;

use Illuminate\Support\Facades\App;

trait Filterable
{
    /**
     * Apply all relevant thread filters.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param BaseFilter $filters
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFilter($query, BaseFilter $filters = null)
    {
        if (! $filters) {
            $filters = App::make($this->filter);
        }

        return $filters->apply($query);
    }
}
