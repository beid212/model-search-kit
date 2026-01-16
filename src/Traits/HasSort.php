<?php

namespace Beid212\ModelSearchKit\Traits;

use Illuminate\Database\Eloquent\Builder;
use Beid212\ModelSearchKit\Sort;

trait HasSort
{
    public function scopeSort(Builder $builder, Sort $sort, array $sortData): Builder
    {
        return $sort->apply($builder, $sortData);
    }
}
