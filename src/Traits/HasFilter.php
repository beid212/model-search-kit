<?php

namespace Beid212\ModelSearchKit\Traits;

use Illuminate\Database\Eloquent\Builder;
use Beid212\ModelSearchKit\Filter;

trait HasFilter
{
    public function scopeFilter(Builder $builder, Filter $filter, array $paramValues): Builder
    {
        return $filter->apply($builder, $paramValues);
    }
}
